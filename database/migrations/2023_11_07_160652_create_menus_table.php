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
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('parent')->default('0');
			$table->integer('group')->default('1');
			$table->string('title')->nullable();
			$table->string('url')->nullable();
			$table->string('class')->nullable();
			$table->string('target')->nullable();
			$table->integer('position')->default('1');
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
        Schema::dropIfExists('menus');
    }
};
