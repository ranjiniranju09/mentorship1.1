<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSessionsTable extends Migration
{
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('modulename_id')->nullable();
            $table->foreign('modulename_id', 'modulename_fk_9752579')->references('id')->on('modules');
            $table->unsignedBigInteger('mentorname_id')->nullable();
            $table->foreign('mentorname_id', 'mentorname_fk_9752580')->references('id')->on('mentors');
            $table->unsignedBigInteger('menteename_id')->nullable();
            $table->foreign('menteename_id', 'menteename_fk_9752581')->references('id')->on('mentees');
        });
    }
}
