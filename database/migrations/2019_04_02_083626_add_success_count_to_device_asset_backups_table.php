<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuccessCountToDeviceAssetBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_asset_backups', function (Blueprint $table) {
            $table->integer('success_count')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_asset_backups', function (Blueprint $table) {
            $table->dropColumn(['success_count']);
        });
    }
}
