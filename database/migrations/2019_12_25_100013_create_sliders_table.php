<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->integer('type')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('publish')->default(true);
            $table->timestamps();
            // relationship with user
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        Schema::create('slides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('slider_id');
            $table->string('url')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('publish')->default(true);
            $table->timestamps();
            // relationship with user, slider
            $table->foreign('slider_id')->references('id')->on('sliders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slides');
        Schema::dropIfExists('sliders');
    }
}
