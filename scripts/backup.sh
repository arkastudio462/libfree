#!/usr/bin/env bash
#
# libfree backup helper.
#
# Dumps the configured database and archives the private storage directory
# (manuscripts and covers), then applies the retention policy. Run it from
# cron/systemd-timer, e.g.:
#
#   15 2 * * * /var/www/libfree/scripts/backup.sh >> /var/log/libfree-backup.log 2>&1
#
# Configuration is read from the environment first, then from .env:
#   BACKUP_DIR              destination directory (default: storage/backups)
#   BACKUP_RETENTION_DAYS   days to keep (default: 14)
#   BACKUP_STORAGE_PATHS    space separated paths relative to the project
#                           (default: storage/app/private)
#   BACKUP_S3_URI           optional offsite target, e.g. s3://bucket/libfree
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="${ENV_FILE:-$ROOT/.env}"
STAMP="$(date +%Y%m%d-%H%M%S)"

log() {
    printf '[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"
}

die() {
    log "ERROR: $*" >&2
    exit 1
}

env_value() {
    local key="$1" value

    value="$(printenv "$key" 2>/dev/null || true)"

    if [ -z "$value" ] && [ -f "$ENV_FILE" ]; then
        value="$(grep -E "^[[:space:]]*${key}=" "$ENV_FILE" | tail -n 1 | cut -d= -f2- || true)"
        value="${value%\"}"
        value="${value#\"}"
        value="${value%\'}"
        value="${value#\'}"
    fi

    printf '%s' "$value"
}

[ -f "$ENV_FILE" ] || die "file environment tidak ditemukan: $ENV_FILE"

DB_CONNECTION="$(env_value DB_CONNECTION)"
DB_CONNECTION="${DB_CONNECTION:-sqlite}"

BACKUP_DIR="$(env_value BACKUP_DIR)"
BACKUP_DIR="${BACKUP_DIR:-$ROOT/storage/backups}"

RETENTION="$(env_value BACKUP_RETENTION_DAYS)"
RETENTION="${RETENTION:-14}"

STORAGE_PATHS="$(env_value BACKUP_STORAGE_PATHS)"
STORAGE_PATHS="${STORAGE_PATHS:-storage/app/private}"

S3_URI="$(env_value BACKUP_S3_URI)"

mkdir -p "$BACKUP_DIR"

TMP_FILES=()
cleanup() {
    for file in "${TMP_FILES[@]:-}"; do
        [ -n "$file" ] && rm -f "$file"
    done
}
trap cleanup EXIT

# ---------------------------------------------------------------------------
# Database
# ---------------------------------------------------------------------------
log "Membuat dump database ($DB_CONNECTION)…"

case "$DB_CONNECTION" in
    sqlite)
        DB_DATABASE="$(env_value DB_DATABASE)"
        DB_DATABASE="${DB_DATABASE:-database/database.sqlite}"
        case "$DB_DATABASE" in
            /*) DB_PATH="$DB_DATABASE" ;;
            *) DB_PATH="$ROOT/$DB_DATABASE" ;;
        esac

        [ -f "$DB_PATH" ] || die "database SQLite tidak ditemukan: $DB_PATH"

        DB_ARCHIVE="$BACKUP_DIR/libfree-db-$STAMP.sqlite.gz"

        if command -v sqlite3 >/dev/null 2>&1; then
            SNAPSHOT="$(mktemp "$BACKUP_DIR/.sqlite-snapshot-XXXXXX")"
            TMP_FILES+=("$SNAPSHOT")
            sqlite3 "$DB_PATH" ".backup '$SNAPSHOT'"
            gzip -c "$SNAPSHOT" > "$DB_ARCHIVE"
        else
            gzip -c "$DB_PATH" > "$DB_ARCHIVE"
        fi
        ;;

    mysql|mariadb)
        command -v mysqldump >/dev/null 2>&1 || die "mysqldump tidak tersedia"

        DB_HOST="$(env_value DB_HOST)"; DB_HOST="${DB_HOST:-127.0.0.1}"
        DB_PORT="$(env_value DB_PORT)"; DB_PORT="${DB_PORT:-3306}"
        DB_DATABASE="$(env_value DB_DATABASE)"; DB_DATABASE="${DB_DATABASE:-libfree}"
        DB_USERNAME="$(env_value DB_USERNAME)"; DB_USERNAME="${DB_USERNAME:-root}"
        DB_PASSWORD="$(env_value DB_PASSWORD)"

        CNF="$(mktemp)"
        chmod 600 "$CNF"
        TMP_FILES+=("$CNF")

        {
            printf '[client]\n'
            printf 'host=%s\n' "$DB_HOST"
            printf 'port=%s\n' "$DB_PORT"
            printf 'user=%s\n' "$DB_USERNAME"
            printf 'password=%s\n' "$DB_PASSWORD"
        } > "$CNF"

        DB_ARCHIVE="$BACKUP_DIR/libfree-db-$STAMP.sql.gz"
        mysqldump --defaults-extra-file="$CNF" \
            --single-transaction --quick --routines --triggers \
            "$DB_DATABASE" | gzip -c > "$DB_ARCHIVE"
        ;;

    pgsql|postgres|postgresql)
        command -v pg_dump >/dev/null 2>&1 || die "pg_dump tidak tersedia"

        DB_HOST="$(env_value DB_HOST)"; DB_HOST="${DB_HOST:-127.0.0.1}"
        DB_PORT="$(env_value DB_PORT)"; DB_PORT="${DB_PORT:-5432}"
        DB_DATABASE="$(env_value DB_DATABASE)"; DB_DATABASE="${DB_DATABASE:-libfree}"
        DB_USERNAME="$(env_value DB_USERNAME)"; DB_USERNAME="${DB_USERNAME:-postgres}"
        DB_PASSWORD="$(env_value DB_PASSWORD)"

        DB_ARCHIVE="$BACKUP_DIR/libfree-db-$STAMP.sql.gz"
        PGPASSWORD="$DB_PASSWORD" pg_dump \
            -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_DATABASE" \
            --no-owner --no-acl | gzip -c > "$DB_ARCHIVE"
        ;;

    *)
        die "DB_CONNECTION tidak didukung: $DB_CONNECTION"
        ;;
esac

[ -s "$DB_ARCHIVE" ] || die "dump database kosong: $DB_ARCHIVE"
log "Database tersimpan: $DB_ARCHIVE"

# ---------------------------------------------------------------------------
# Private storage (manuscripts, covers, and other uploads)
# ---------------------------------------------------------------------------
read -r -a PATHS <<< "$STORAGE_PATHS"
EXISTING_PATHS=()

for path in "${PATHS[@]}"; do
    if [ -e "$ROOT/$path" ]; then
        EXISTING_PATHS+=("$path")
    else
        log "Lewati path yang tidak ada: $path"
    fi
done

if [ "${#EXISTING_PATHS[@]}" -gt 0 ]; then
    FILES_ARCHIVE="$BACKUP_DIR/libfree-files-$STAMP.tar.gz"
    tar -czf "$FILES_ARCHIVE" -C "$ROOT" "${EXISTING_PATHS[@]}"
    log "Berkas privat tersimpan: $FILES_ARCHIVE"
else
    log "Tidak ada path penyimpanan yang bisa diarsipkan."
fi

# ---------------------------------------------------------------------------
# Optional offsite copy
# ---------------------------------------------------------------------------
if [ -n "$S3_URI" ]; then
    if command -v aws >/dev/null 2>&1; then
        log "Mengunggah backup ke $S3_URI…"
        aws s3 cp "$BACKUP_DIR/libfree-db-$STAMP"* "$S3_URI/$(date +%Y/%m)/"
        [ -n "${FILES_ARCHIVE:-}" ] && aws s3 cp "$FILES_ARCHIVE" "$S3_URI/$(date +%Y/%m)/"
    else
        log "PERINGATAN: BACKUP_S3_URI diisi tetapi AWS CLI tidak tersedia."
    fi
fi

# ---------------------------------------------------------------------------
# Retention
# ---------------------------------------------------------------------------
DELETED="$(find "$BACKUP_DIR" -maxdepth 1 -type f -name 'libfree-*' -mtime +"$RETENTION" -print -delete | wc -l)"
log "Retensi: menghapus $DELETED berkas lebih dari $RETENTION hari."

log "Backup selesai."
