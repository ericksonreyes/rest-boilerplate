<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_id', 150)->unique();
            $table->string('event_name', 255);
            $table->integer('happened_on');
            $table->string('context_name', 150);
            $table->string('entity_type', 150);
            $table->string('entity_id', 150);
            $table->longText('event_data');
            $table->longText('event_meta_data')->nullable();
            $table->index(['event_id'], 'idx_event_id');
            $table->index(['entity_id'], 'idx_entity_id');
            $table->index(['context_name', 'entity_type', 'entity_id'], 'idx_by_entity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_events');
    }
}
