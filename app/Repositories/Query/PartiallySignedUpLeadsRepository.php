<?php

namespace App\Repositories\Query;

interface PartiallySignedUpLeadsRepository
{
    /**
     * @param string $leadId
     * @param string $token
     * @param string $code
     * @return PartiallySignedUpLead|null
     */
    public function findByLeadIdTokenAndCode(string $leadId, string $token, string $code): ?PartiallySignedUpLead;

    /**
     * @param string $token
     * @return PartiallySignedUpLead|null
     */
    public function findByToken(string $token): ?PartiallySignedUpLead;

    /**
     * @param $leadId
     * @return array|null
     */
    public function findAllByLeadId(string $leadId): ?array;

    /**
     * @param PartiallySignedUpLead $leadId
     * @return bool
     */
    public function markLeadAsClosed(PartiallySignedUpLead $leadId): bool;
}
