<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateMenteesTable extends Migration
{
    public function up()
    {
        Schema::create('mentees', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key column
            $table->string('name'); // Name of the mentee
            $table->string('email')->unique(); // Unique email
            $table->bigInteger('mobile')->nullable(); // Mobile number, no length specified
            $table->date('dob')->nullable(); // Date of birth
            $table->string('skills')->nullable(); // Skills column
            $table->string('interestedskills'); // Interested skills
            $table->timestamps(); // Created at and updated at
            $table->softDeletes(); // Deleted at for soft deletes
            // Add foreign key constraint for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('mentees');
    }
}