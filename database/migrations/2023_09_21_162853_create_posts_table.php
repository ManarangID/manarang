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
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('category_id')->default('1');
			$table->string('title')->nullable();
			$table->string('seotitle')->nullable();
			$table->text('content')->nullable();
			$table->text('meta_description')->nullable();
			$table->string('picture', 2048)->nullable();
			$table->string('picture_description')->nullable();
			$table->string('tag')->nullable();
			$table->enum('type', ['general', 'pagination', 'picture', 'video'])->default('general');
			$table->enum('active', ['Y', 'N'])->default('Y');
			$table->enum('headline', ['Y', 'N'])->default('Y');
			$table->enum('comment', ['Y', 'N'])->default('Y');
			$table->integer('hits')->default('1');
			$table->integer('likes')->default('1');
			$table->integer('created_by')->default('1');
			$table->integer('updated_by')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
