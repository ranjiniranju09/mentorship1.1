<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagespokenMentorPivotTable extends Migration
{
    public function up()
    {
        Schema::create('languagespoken_mentor', function (Blueprint $table) {
            $table->unsignedBigInteger('mentor_id');
            $table->foreign('mentor_id', 'mentor_id_fk_9752592')->references('id')->on('mentors')->onDelete('cascade');
            $table->unsignedBigInteger('languagespoken_id');
            $table->foreign('languagespoken_id', 'languagespoken_id_fk_9752592')->references('id')->on('languagespokens')->onDelete('cascade');
        });
    }
}
