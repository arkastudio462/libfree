<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('license_status')->default('unverified')->after('reader_content');
            $table->string('license_name')->nullable()->after('license_status');
            $table->string('license_url', 2048)->nullable()->after('license_name');
            $table->string('source_url', 2048)->nullable()->after('license_url');
            $table->text('audit_notes')->nullable()->after('source_url');
            $table->boolean('is_published')->default(false)->after('audit_notes');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->timestamp('audited_at')->nullable()->after('published_at');

            $table->index(['is_published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'published_at']);
            $table->dropColumn([
                'license_status',
                'license_name',
                'license_url',
                'source_url',
                'audit_notes',
                'is_published',
                'published_at',
                'audited_at',
            ]);
        });
    }
};
