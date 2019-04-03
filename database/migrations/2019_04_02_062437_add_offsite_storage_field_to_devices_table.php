<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffsiteStorageFieldToDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->bigInteger('offsite_storage_used')->nullable()->default(0)->after('ls_available_unit');
            $table->string('offsite_storage_unit', 2)->nullable()->after('ls_available_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['offsite_storage_used', 'offsite_storage_unit']);
        });
    }
}
