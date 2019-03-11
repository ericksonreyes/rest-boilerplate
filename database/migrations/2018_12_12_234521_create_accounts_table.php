<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id', 150)->unique();
            $table->string('email', 255);
            $table->string('status', 50);
            $table->integer('closed_on')->nullable();
            $table->string('closed_by', 150);
            $table->index(['account_id'], 'indexed_token');
            $table->index(['email'], 'indexed_email');
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
        Schema::dropIfExists('sales_accounts');
    }
}
