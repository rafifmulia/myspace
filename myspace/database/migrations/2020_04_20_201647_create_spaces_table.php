<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spaces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 45);
            $table->string('description', 45);
            $table->string('region', 45);
            $table->string('district', 45);
            $table->string('village', 45);
            $table->string('lat', 45);
            $table->string('lng', 45);
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spaces');
    }
}
