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
            $table->string('manuscript_path')->nullable()->after('reader_content');
            $table->string('manuscript_name')->nullable()->after('manuscript_path');
            $table->unsignedBigInteger('manuscript_size')->nullable()->after('manuscript_name');
            $table->string('manuscript_mime')->nullable()->after('manuscript_size');
            $table->string('manuscript_hash', 64)->nullable()->after('manuscript_mime');
            $table->string('cover_path')->nullable()->after('manuscript_hash');
            $table->string('cover_mime')->nullable()->after('cover_path');
            $table->unsignedInteger('download_count')->default(0)->after('cover_mime');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->foreignId('book_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->string('manuscript_path')->nullable()->after('notes');
            $table->string('manuscript_name')->nullable()->after('manuscript_path');
            $table->unsignedBigInteger('manuscript_size')->nullable()->after('manuscript_name');
            $table->string('manuscript_mime')->nullable()->after('manuscript_size');
            $table->string('manuscript_hash', 64)->nullable()->after('manuscript_mime');
            $table->string('cover_path')->nullable()->after('manuscript_hash');
            $table->string('cover_name')->nullable()->after('cover_path');
            $table->string('cover_mime')->nullable()->after('cover_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('book_id');
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn([
                'manuscript_path',
                'manuscript_name',
                'manuscript_size',
                'manuscript_mime',
                'manuscript_hash',
                'cover_path',
                'cover_name',
                'cover_mime',
            ]);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'manuscript_path',
                'manuscript_name',
                'manuscript_size',
                'manuscript_mime',
                'manuscript_hash',
                'cover_path',
                'cover_mime',
                'download_count',
            ]);
        });
    }
};
