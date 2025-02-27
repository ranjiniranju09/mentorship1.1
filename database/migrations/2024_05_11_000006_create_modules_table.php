<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('name'); // Module name
            $table->longText('description')->nullable(); // Module description, allowing for long content, nullable
            $table->longText('mentorsnote')->nullable(); // Mentors' note, nullable
            $table->longText('objective')->nullable(); // Module objective, nullable
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); // Deleted_at field for soft deletes
        });
    }
}
