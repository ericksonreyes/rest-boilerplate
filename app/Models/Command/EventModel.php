<?php

namespace App\Models\Command;

use Illuminate\Database\Eloquent\Model;

class EventModel extends Model
{

    /**
     * @var string
     */
    protected $table = 'sales_events';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Prevent delete
         */
        self::deleting(function () {
            return false;
        });

        /**
         * Prevent update
         */
        self::updating(function () {
            return false;
        });
    }
}
