<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');                // Primary key
            $table->string('question_text')->nullable(); // Question text, nullable
            $table->integer('points')->nullable();       // Points, nullable
            $table->enum('mcq', ['Yes', 'No'])->default('No'); // Default is 'No'
            // $table->unsignedBigInteger('test_id')->nullable(); // Foreign key to chapter_tests
            $table->timestamps();                        // Created and updated timestamps
            $table->softDeletes();                       // Soft delete column

            // Define foreign key relationship
        //     $table->foreign('test_id')
        //           ->references('id')
        //           ->on('tests')
        //           ->onDelete('set null'); // Set test_id to NULL if the test is deleted
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
