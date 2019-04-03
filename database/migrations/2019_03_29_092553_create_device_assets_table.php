<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('device_sn', 12);
            $table->string('name')->nullable();
            $table->string('volume')->nullable();
            $table->string('local_ip')->nullable();
            $table->string('os')->nullable();
            $table->integer('protected_volume_count');
            $table->integer('unprotected_volume_count');
            $table->string('agent_version')->nullable();
            $table->tinyInteger('is_paused')->unsigned();
            $table->tinyInteger('is_archived')->unsigned();
            $table->string('latest_offsite')->nullable();
            $table->integer('local_snapshot')->unsigned();
            $table->string('fqdn')->nullable();
            $table->string('type', 15)->nullable();
            $table->timestamps();


            $table->foreign('device_sn')->on('devices')->references('sn')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_assets');
    }
}
