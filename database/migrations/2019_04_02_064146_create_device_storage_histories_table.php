<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceStorageHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_storage_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('device_sn',12);
            $table->bigInteger('ls_used_size')->unsigned();
            $table->string('ls_used_unit', 2);
            $table->bigInteger('ls_available_size')->unsigned();
            $table->string('ls_available_unit', 2);
            $table->bigInteger('offsite_storage_used')->nullable()->default(0);
            $table->string('offsite_storage_unit', 2)->nullable();
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
        Schema::dropIfExists('device_storage_histories');
    }
}
