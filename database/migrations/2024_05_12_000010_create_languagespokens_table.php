<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagespokensTable extends Migration
{
    public function up()
    {
        Schema::create('languagespokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('langname');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
