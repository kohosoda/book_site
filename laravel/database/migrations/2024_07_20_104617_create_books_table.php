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
            $table->string("name", 255)->unique();
            $table->datetimes();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string("google_id", 255)->unique();
            $table->string("title", 1000);
            $table->unsignedInteger("price")->nullable();
            $table->unsignedInteger("page_count")->nullable();
            $table->string("publisher", 255)->nullable();
            $table->datetime("published_date")->nullable();
            $table->foreignId("category_id")->constrained()->onDelete('cascade');
            $table->text("description");
            $table->datetimes();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255)->unique();
            $table->datetimes();
        });

        Schema::create('rel_books_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId("book_id")->constrained()->onDelete('cascade');
            $table->foreignId("author_id")->constrained()->onDelete('cascade');
            $table->unique(['book_id', 'author_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rel_books_authors');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('books');
        Schema::dropIfExists('categories');
    }
};
