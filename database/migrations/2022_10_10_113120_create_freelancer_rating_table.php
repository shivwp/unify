<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelancerRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancer_rating', function (Blueprint $table) {
            $table->id();
            $table->biginteger('freelancer_id')->nullable();
            $table->biginteger('client_id')->nullable();
            $table->biginteger('project_id')->nullable();
            $table->integer('rating')->nullable();
            $table->longtext('description')->nullable();
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
        Schema::dropIfExists('freelancer_rating');
    }
}
