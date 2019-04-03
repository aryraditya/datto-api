<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceAssetBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_asset_backups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->bigInteger('timestamp');
            $table->string('backup_status')->nullable();
            $table->text('backup_status_message')->nullable();
            $table->string('local_verify_status')->nullable();
            $table->text('local_verify_errors')->nullable();
            $table->string('advanced_verify_status')->nullable();
            $table->text('screenshot_verify_image')->nullable();
            $table->timestamps();


            $table->foreign('asset_id')->on('device_assets')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_asset_backups');
    }
}
