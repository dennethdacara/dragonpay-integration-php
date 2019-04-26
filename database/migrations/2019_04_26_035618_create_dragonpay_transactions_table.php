<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDragonpayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dragonpay_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('txn_id');
            $table->string('ref_no');
            $table->string('merchant', 100);
            $table->decimal('amount', 10,2);
            $table->string('ccy', 50)->nullable();
            $table->string('status', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('processor')->nullable();
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
        Schema::dropIfExists('dragonpay_transactions');
    }
}
