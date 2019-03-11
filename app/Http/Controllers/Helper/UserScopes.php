<?php

namespace App\Http\Controllers\Helper;

use Rest\Security\Domain\Model\Scope\AllowedAction\Admin as AdminAction;
use Rest\Security\Domain\Model\Scope\UserType\Admin;

class UserScopes
{
    /**
     * @var UserScope[]
     */
    private $scopes = [];

    /**
     * @param string $scope
     */
    public function addScope(string $scopeString): void
    {
        $this->scopes[] = new UserScope($scopeString);
    }

    /**
     * @return UserScope[]
     */
    public function scopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param array $scopeArray
     */
    public function addFromArray(array $scopeArray): void
    {
        foreach ($scopeArray as $scopeString) {
            $this->addScope($scopeString);
        }
    }

    /**
     * @param $context
     * @param $model
     * @return bool
     */
    public function isAdmin($context, $model): bool
    {
        foreach ($this->scopes() as $scope) {
            if ($scope->context() === $context && $scope->model() === $model) {
                if ($scope->isAdmin()) {
                    return true;
                }
            }
        }

        return false;
    }
}
