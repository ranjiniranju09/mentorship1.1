<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_answers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->longText('discussion_answer'); // Changed to singular as per field name
            $table->longText('mentorsreply')->nullable(); // Allows null values for mentor's reply
            $table->unsignedBigInteger('question_id'); // Corrected type to unsignedBigInteger for foreign key
            $table->unsignedBigInteger('menteename_id'); // New column for mentee reference
            $table->timestamps(); // created_at and updated_at fields
            $table->softDeletes(); // deleted_at field for soft deletes
        
            // Foreign key constraints
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('menteename_id')->references('id')->on('mentees')->onDelete('cascade'); // Assuming a 'mentees' table exists
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discussion_answers');
    }
}

