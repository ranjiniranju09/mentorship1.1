<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('ticket_descriptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/
        Schema::create('ticket_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('ticket_description');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ticket_title')->nullable();
            $table->string('attachment_url')->nullable();
            $table->longText('response');
            $table->unsignedBigInteger('ticket_category_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_descriptions');
    }
}
