<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_devices', function (Blueprint $table) {
            $table->char('device_sn', 12);
            $table->integer('company_id')->unsigned();
            $table->timestamps();

            $table->primary(['device_sn','company_id']);

            $table->foreign('device_sn')->on('devices')->references('sn')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->on('companies')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_devices');
    }
}
