<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('module_id')->nullable(); // Foreign key to modules
            $table->unsignedBigInteger('chapter_id')->nullable(); // Foreign key to chapters (added this)
            $table->string('title')->nullable(); // Test title
            $table->boolean('is_published')->default(0); // Publish status (0 = unpublished, 1 = published)
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); // deleted_at field for soft deletes
        
            // Define foreign key relationships
            $table->foreign('module_id')
                  ->references('id')
                  ->on('modules')
                  ->onDelete('set null'); // Set module_id to NULL if the module is deleted
        
            $table->foreign('chapter_id')  // Added this foreign key constraint
                  ->references('id')
                  ->on('chapters')
                  ->onDelete('set null'); // Set chapter_id to NULL if the chapter is deleted
        
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
