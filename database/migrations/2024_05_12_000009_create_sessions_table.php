<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('sessiondatetime');
            $table->string('sessionlink');
            $table->string('session_title');
            $table->integer('session_duration_minutes')->nullable(); // Change to integer
            $table->boolean('done')->default(false); // Change to boolean with a default value
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
