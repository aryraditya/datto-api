<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->char('sn', 12)->primary();
            $table->string('name', 125)->nullable();
            $table->string('model', 50)->nullable();
            $table->bigInteger('last_seen_ts')->nullable();
            $table->string('last_seen_tz')->nullable();
            $table->tinyInteger('hidden')->unsigned()->nullable();
            $table->integer('active_ticket')->unsigned()->default(0);
            $table->string('service_plan')->nullable();
            $table->bigInteger('registration_date_ts')->nullable();
            $table->string('registration_date_tz')->nullable();
            $table->bigInteger('service_period_ts')->nullable();
            $table->string('service_period_tz')->nullable();
            $table->bigInteger('warranty_expire_ts')->nullable();
            $table->string('warranty_expire_tz')->nullable();
            $table->bigInteger('ls_used_size')->unsigned();
            $table->string('ls_used_unit', 2);
            $table->bigInteger('ls_available_size')->unsigned();
            $table->string('ls_available_unit', 2);
            $table->string('internal_ip', 20)->nullable();
            $table->integer('agent_count')->nullable()->default(0)->unsigned();
            $table->integer('share_count')->nullable()->default(0)->unsigned();
            $table->integer('alert_count')->nullable()->default(0)->unsigned();
            $table->timestamp('synced_at')->nullable();
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
        Schema::dropIfExists('devices');
    }
}
