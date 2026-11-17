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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_name')->nullable();
            $table->string('initials', 8)->nullable();
            $table->string('avatar_class')->nullable();
            $table->string('line')->nullable();
            $table->string('badge')->nullable();
            $table->text('bio')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('photo_url')->nullable();
            $table->unsignedInteger('favorite_rank')->nullable();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->text('synopsis');
            $table->text('quote')->nullable();
            $table->string('quote_source')->nullable();
            $table->string('quote_source_role')->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->string('reads_label')->nullable();
            $table->unsignedInteger('bookmark_count')->default(0);
            $table->json('rating_dist')->nullable();
            $table->json('formats');
            $table->json('sizes')->nullable();
            $table->json('card')->nullable();
            $table->json('showcase')->nullable();
            $table->json('detail')->nullable();
            $table->unsignedInteger('popularity')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('featured_rank')->nullable();
            $table->boolean('is_trending')->default(false);
            $table->unsignedSmallInteger('trend_rank')->nullable();
            $table->unsignedSmallInteger('rank')->nullable();
            $table->unsignedSmallInteger('related_order')->nullable();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('initials', 8);
            $table->string('avatar_class');
            $table->string('badge_text');
            $table->string('badge_class');
            $table->string('meta');
            $table->decimal('rating', 2, 1);
            $table->text('body');
            $table->unsignedInteger('helpfuls')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('books');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('categories');
    }
};
