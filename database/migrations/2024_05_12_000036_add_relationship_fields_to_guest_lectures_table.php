<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToGuestLecturesTable extends Migration
{
    public function up()
    {
        Schema::table('guest_lectures', function (Blueprint $table) {
            $table->unsignedBigInteger('speaker_name_id')->nullable();
            $table->foreign('speaker_name_id', 'speaker_name_fk_9772330')->references('id')->on('guestspeakers');
        });
    }
}
