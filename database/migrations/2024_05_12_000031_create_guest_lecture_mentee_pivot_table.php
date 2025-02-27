<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestLectureMenteePivotTable extends Migration
{
    public function up()
    {
        Schema::create('guest_lecture_mentee', function (Blueprint $table) {
            $table->unsignedBigInteger('guest_lecture_id');
            $table->foreign('guest_lecture_id')->references('id')->on('guest_lectures')->onDelete('cascade');
            
            $table->unsignedBigInteger('mentee_id');
            $table->foreign('mentee_id')->references('id')->on('mentees')->onDelete('cascade');

            // Add timestamps to the pivot table
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_lecture_mentee');
    }
}
