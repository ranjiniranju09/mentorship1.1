<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('type'); // 'module' or 'normal'
            $table->unsignedBigInteger('module_id')->nullable();
            $table->unsignedBigInteger('added_by'); // Admin or Mentor ID
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            $table->softDeletes();
    
    //$table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
