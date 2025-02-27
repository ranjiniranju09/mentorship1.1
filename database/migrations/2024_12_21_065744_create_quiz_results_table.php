<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->unsignedBigInteger('module_id'); // Foreign key to modules table
            $table->unsignedBigInteger('tests_id')->nullable();
            $table->integer('score'); // Score for the quiz
            $table->integer('attempts')->default(1); // Default number of attempts is 1
            $table->integer('total_points'); // Maximum possible points
            $table->timestamps(); // created_at and updated_at fields
        
            // Foreign key constraints
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_results');
    }
}
