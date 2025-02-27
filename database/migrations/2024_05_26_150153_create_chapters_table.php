<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('chaptername'); // Name of the chapter
            $table->longText('description'); // Description of the chapter, changed to longText
            $table->longText('mentorsnote')->nullable(); // Optional mentor's note
            $table->longText('objective')->nullable(); // Optional objective of the chapter
            $table->enum('published', ['yes', 'no'])->default('yes'); // Publication status with default 'no'
            $table->unsignedBigInteger('module_id')->nullable(); // Foreign key to the modules table
            $table->unsignedBigInteger('chapter_id')->nullable(); // Optional parent chapter ID
            $table->timestamps(); // created_at and updated_at fields
            $table->softDeletes(); // deleted_at field for soft deletes
        
            // Define foreign key relationships
            $table->foreign('module_id')
                  ->references('id')
                  ->on('modules')
                  ->onDelete('set null'); // Set module_id to NULL if the related module is deleted
        
            $table->foreign('chapter_id')
                  ->references('id')
                  ->on('chapters')
                  ->onDelete('set null'); // Set chapter_id to NULL if the parent chapter is deleted
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapters');
    }
}
