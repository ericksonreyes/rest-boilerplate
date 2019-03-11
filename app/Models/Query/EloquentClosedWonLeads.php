<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Model;
use Rest\Sales\Application\Lead\Service\LeadEmailAvailabilityService;

class EloquentClosedWonLeads extends Model implements LeadEmailAvailabilityService
{
    /**
     * @var string
     */
    protected $table = 'sales_closed_won_leads';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param string $email
     * @return bool
     */
    public function isLeadEmailAlreadyInUse(string $email): bool
    {
        return $this->where('email', $email)->count() > 0;
    }
}
