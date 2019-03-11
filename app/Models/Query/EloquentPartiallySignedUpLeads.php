<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 3:56 PM
 */

namespace App\Models\Query;

use App\Repositories\Query\PartiallySignedUpLead;
use App\Repositories\Query\PartiallySignedUpLeadDTO;
use App\Repositories\Query\PartiallySignedUpLeadsRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentPartiallySignedUpLeads extends Model implements PartiallySignedUpLeadsRepository
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'sales_partially_signed_up_leads';

    /**
     * @param string $leadId
     * @param string $token
     * @param string $code
     * @return PartiallySignedUpLead|null
     */
    public function findByLeadIdTokenAndCode(string $leadId, string $token, string $code): ?PartiallySignedUpLead
    {
        $model = $this->where('lead_id', $leadId)
            ->where('token', $token)
            ->where('code', $code)
            ->first();

        if ($model) {
            return new PartiallySignedUpLeadDTO(
                $model->lead_id,
                $model->email,
                $model->token,
                $model->code,
                $model->signedup_on
            );
        }

        return null;
    }

    /**
     * @param string $token
     * @return PartiallySignedUpLead|null
     */
    public function findByToken(string $token): ?PartiallySignedUpLead
    {
        $model = $this->where('token', $token)->first();
        if ($model) {
            return new PartiallySignedUpLeadDTO(
                $model->lead_id,
                $model->email,
                $model->token,
                $model->code,
                $model->signedup_on
            );
        }

        return null;
    }

    /**
     * @param $leadId
     * @return PartiallySignedUpLead[]|null
     */
    public function findAllByLeadId(string $leadId): ?array
    {
        $models = $this->where('lead_id', $leadId)
            ->orderBy('signedup_on', 'asc')
            ->get();

        $tokens = [];
        foreach ($models as $model) {
            $tokens[] = new PartiallySignedUpLeadDTO(
                $model->lead_id,
                $model->email,
                $model->token,
                $model->code,
                $model->signedup_on
            );
        }

        return $tokens;
    }

    /**
     * @param PartiallySignedUpLead $partiallySignedUpLead
     * @return bool
     */
    public function markLeadAsClosed(PartiallySignedUpLead $partiallySignedUpLead): bool
    {
        $model = $this->where('lead_id', $partiallySignedUpLead->leadId())
            ->where('token', $partiallySignedUpLead->token())
            ->where('code', $partiallySignedUpLead->code())
            ->first();

        if ($model) {
            return $model->delete();
        }

        return false;
    }
}
