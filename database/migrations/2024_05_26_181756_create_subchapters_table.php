<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubchaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('subchapters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/
        Schema::create('subchapters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('content');
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // public function down()
    // {
    //     Schema::dropIfExists('subchapters');
        
    // }
    public function down()
    {
        Schema::table('subchapters', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
