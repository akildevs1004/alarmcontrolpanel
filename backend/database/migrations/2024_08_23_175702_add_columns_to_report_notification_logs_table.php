<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_alarm_notes', function (Blueprint $table) {
            $table->string("email")->nullable();
            $table->string("whatsapp_number")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_alarm_notes', function (Blueprint $table) {
            $table->dropColumn("email");
            $table->dropColumn("whatsapp_number");
        });
    }
};
