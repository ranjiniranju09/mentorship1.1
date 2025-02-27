<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestLecturesTable extends Migration
{
    public function up()
    {
        Schema::create('guest_lectures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('guessionsession_title');
            $table->datetime('guestsession_date_time');
            $table->string('guest_session_duration');
            $table->string('platform');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
