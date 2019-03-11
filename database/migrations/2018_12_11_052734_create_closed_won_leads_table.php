<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosedWonLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_closed_won_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lead_id', 150)->unique();
            $table->string('account_id', 150)->unique();
            $table->string('email', 255)->unique();
            $table->integer('closed_on');
            $table->index(['lead_id'], 'unique_lead_id');
            $table->index(['email'], 'unique_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_closed_won_leads');
    }
}
