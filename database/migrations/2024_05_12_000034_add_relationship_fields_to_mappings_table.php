<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToMappingsTable extends Migration
{
    public function up()
    {
        Schema::table('mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('mentorname_id')->nullable();
            $table->foreign('mentorname_id', 'mentorname_fk_9752600')->references('id')->on('mentors');
            $table->unsignedBigInteger('menteename_id')->nullable();
            $table->foreign('menteename_id', 'menteename_fk_9752601')->references('id')->on('mentees');
        });
    }
}
