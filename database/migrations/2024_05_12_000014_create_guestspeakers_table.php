<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestspeakersTable extends Migration
{
    public function up()
    {
        Schema::create('guestspeakers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('speakername');
            $table->string('speaker_name');
            $table->bigint('speakermobile');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
