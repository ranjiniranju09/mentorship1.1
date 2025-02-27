<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionRecordingsTable extends Migration
{
    public function up()
    {
        Schema::create('session_recordings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
