<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagespokenMenteePivotTable extends Migration
{
    public function up()
    {
        Schema::create('languagespoken_mentee', function (Blueprint $table) {
            $table->unsignedBigInteger('mentee_id');
            $table->foreign('mentee_id', 'mentee_id_fk_9752593')->references('id')->on('mentees')->onDelete('cascade');
            $table->unsignedBigInteger('languagespoken_id');
            $table->foreign('languagespoken_id', 'languagespoken_id_fk_9752593')->references('id')->on('languagespokens')->onDelete('cascade');
        });
    }
}
