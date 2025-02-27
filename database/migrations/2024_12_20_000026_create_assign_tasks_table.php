<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignTasksTable extends Migration
{
    public function up()
    {
        Schema::create('assign_tasks', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('title'); // Task title
            $table->longText('description'); // Task description
            $table->string('files')->nullable(); // For file uploads, nullable
            $table->datetime('start_date_time'); // Start date and time
            $table->datetime('end_date_time'); // End date and time
            $table->unsignedBigInteger('mentor_id'); // Foreign key to mentors
            $table->unsignedBigInteger('mentee_id'); // Foreign key to mentees
            $table->boolean('submitted')->default(0)->nullable(); // Indicates submission status, nullable
            $table->string('completed')->default('open')->nullable(); // Indicates completion status, nullable
            $table->longText('task_response')->nullable(); // Mentee's response to the task, nullable
            $table->text('mentors_reply')->nullable();
            $table->string('submitted_file')->nullable(); // Path to submitted file, nullable
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); // Deleted_at field for soft deletes
        
            // Foreign key constraints
            // $table->foreign('mentor_id')
            //       ->references('id')
            //       ->on('users') // Assuming mentors are stored in the users table
            //       ->onDelete('cascade'); // Delete task if mentor is deleted
        
            // $table->foreign('mentee_id')
            //       ->references('id')
            //       ->on('users') // Assuming mentees are stored in the users table
                //   ->onDelete('cascade'); // Delete task if mentee is deleted
        });
        
    }
}
