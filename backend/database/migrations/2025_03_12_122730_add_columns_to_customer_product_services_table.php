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
        Schema::table('customer_product_services', function (Blueprint $table) {
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->integer("invoices_count")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_product_services', function (Blueprint $table) {
            $table->dropColumn("start_date");
            $table->dropColumn("end_date");
            $table->dropColumn("invoices_count");
        });
    }
};
