<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartialLeadSignUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_partially_signed_up_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 150)->unique();
            $table->string('code', 10);
            $table->string('lead_id', 150);
            $table->string('email', 255);
            $table->integer('signedup_on');
            $table->index(['token'], 'indexed_token');
            $table->index(['lead_id'], 'indexed_lead_id');
            $table->index(['token', 'lead_id', 'code'], 'indexed_token_lead_id_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_partially_signed_up_leads');
    }
}
