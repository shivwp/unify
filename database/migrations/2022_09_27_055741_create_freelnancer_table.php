<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreelnancerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelnancer', function (Blueprint $table) {
            $table->id();
            $table->biginteger('user_id');
            $table->string('occcuption')->nullable();
            $table->longtext('description')->nullable();
            $table->longtext('intro_video')->nullable();
            $table->enum('payment_base',['fixed','hourly'])->default('fixed');
            $table->decimal('amount', 10,2)->default(0);
            $table->integer('rating')->default(0);
            $table->biginteger('plan_id')->nullable();
            $table->decimal('total_earning', 10,2)->default(0);
            $table->biginteger('total_jobs')->default(0);
            $table->time('total_hours')->nullable();
            $table->biginteger('pending_project')->default(0);
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
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
        Schema::dropIfExists('freelnancer');
    }
}
