<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenteeSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mentee_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentee_id')->constrained()->onDelete('cascade'); // Foreign key for mentee
            $table->foreignId('skill_id')->constrained()->onDelete('cascade'); // Foreign key for skill
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mentee_skills');
    }
}
