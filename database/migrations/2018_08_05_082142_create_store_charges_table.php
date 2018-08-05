<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('store_charges', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('store_id');
        $table->string('name');
        $table->string('shopify_charge_id');
        $table->string('shopify_plan');
        $table->integer('quantity');
        $table->integer('charge_type');
        $table->boolean('test');
        $table->timestamp('trial_ends_at')->nullable();
        $table->timestamp('ends_at')->nullable();
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
        Schema::dropIfExists('store_charges');
    }
}
