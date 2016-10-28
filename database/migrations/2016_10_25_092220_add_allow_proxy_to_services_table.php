<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowProxyToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cas_services', function (Blueprint $table) {
            $table->boolean('allow_proxy')->default(false)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cas_services', function (Blueprint $table) {
            $table->dropColumn('allow_proxy');
        });
    }
}
