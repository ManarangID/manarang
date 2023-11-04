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
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('parent')->default('0');
			$table->integer('post_id')->nullable();
			$table->string('name')->nullable();
			$table->string('email')->nullable();
			$table->text('content')->nullable();
			$table->enum('active', ['Y', 'N'])->default('N');
			$table->enum('status', ['Y', 'N'])->default('N');
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
        Schema::dropIfExists('comments');
    }
};
