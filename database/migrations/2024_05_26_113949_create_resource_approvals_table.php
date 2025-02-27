<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /*
        Schema::create('resource_approvals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });*/

        Schema::create('resource_approvals', function (Blueprint $table) {
            
        $table->id();
        $table->unsignedBigInteger('resource_id');
        $table->unsignedBigInteger('admin_id');
        $table->boolean('is_approved');
        $table->text('comments')->nullable();
        $table->timestamps();
    
    //$table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
    //$table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade'); // assuming users table for admins
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_approvals');
    }
}
