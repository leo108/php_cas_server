<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cas_service_hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('host')->unique();
            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')->references('id')->on('cas_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cas_service_hosts');
    }
}
