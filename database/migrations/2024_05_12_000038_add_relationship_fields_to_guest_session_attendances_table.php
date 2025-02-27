<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToGuestSessionAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('guest_session_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('session_title_id')->nullable();
            $table->foreign('session_title_id', 'session_title_fk_9772342')->references('id')->on('sessions');
        });
    }
}
