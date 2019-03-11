<?php

namespace App\Repositories\Command;

use Rest\Shared\IdentityGenerator;
use Ramsey\Uuid\Uuid;

class RamseyUuidGenerator implements IdentityGenerator
{
    /**
     * @param string $prefix
     * @return string
     * @throws \Exception
     */
    public function nextIdentity($prefix = ''): string
    {
        return $prefix . Uuid::uuid4()->toString();
    }
}
