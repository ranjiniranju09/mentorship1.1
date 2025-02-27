<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverallmoduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overallmodule', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('mentorname_id'); // Foreign key referencing the mentor
            $table->unsignedBigInteger('menteename_id'); // Foreign key referencing the mentee
            $table->unsignedBigInteger('module_id'); // Foreign key referencing the module
            
            // Additional fields
            $table->integer('score')->nullable(); // Score field to track the mentee's score in the module
            $table->timestamp('completed_at')->nullable(); // Timestamp when the module was completed
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes(); // Soft delete for the record

            // Foreign key constraints
            $table->foreign('mentorname_id')->references('id')->on('mentors')->onDelete('cascade');
            $table->foreign('menteename_id')->references('id')->on('mentees')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overallmodule');
    }
}
