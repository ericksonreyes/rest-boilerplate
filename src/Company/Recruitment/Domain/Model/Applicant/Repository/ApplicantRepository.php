<?php

namespace Company\Recruitment\Domain\Model\Employee\Repository;


use Company\Recruitment\Domain\Model\Applicant\Applicant;

interface ApplicantRepository
{

    /**
     * @param string $userId
     * @return Applicant|null
     */
    public function findById(string $userId): ?Applicant;

    /**
     * @param Applicant $user
     */
    public function store(Applicant $user): void;
}
