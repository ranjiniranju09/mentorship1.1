<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOneOneAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('one_one_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('session_attendance_id')->nullable();
            $table->foreign('session_attendance_id', 'session_attendance_fk_9772337')->references('id')->on('sessions');
        });
    }
}
