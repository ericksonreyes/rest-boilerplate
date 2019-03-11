<?php

namespace App\Http\Controllers\Helper;

use Rest\Security\Domain\Model\Scope\AllowedAction\Admin as AdminAction;
use Rest\Security\Domain\Model\Scope\UserType\Admin;

/**
 * Class UserScope
 * @package App\Http\Controllers
 */
class UserScope
{

    /**
     * @var string
     */
    private $userScopeString;

    /**
     * @var string
     */
    private $context = '';

    /**
     * @var string
     */
    private $model = '';

    /**
     * @var string
     */
    private $userType = '';

    /**
     * @var string[]
     */
    private $allowedActions = [];

    /**
     * UserScope constructor.
     * @param string $userScopeString
     */
    public function __construct(string $userScopeString)
    {
        $this->userScopeString = $userScopeString;

        $userScopeArray = explode(':', $userScopeString);
        $this->context = isset($userScopeArray[0]) ? $userScopeArray[0] : '';
        $this->model = isset($userScopeArray[1]) ? $userScopeArray[1] : '';
        $this->userType = isset($userScopeArray[2]) ? $userScopeArray[2] : '';

        $actions = isset($userScopeArray[3]) ? explode(',', $userScopeArray[3]) : [];
        foreach ((array)$actions as $action) {
            $this->allowedActions[] = $action;
        }
    }

    /**
     * @param string $userScope
     * @return UserScope
     */
    public static function make(string $userScope): UserScope
    {
        return new static($userScope);
    }

    /**
     * @return string
     */
    public function context(): string
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function model(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function userType(): string
    {
        return $this->userType;
    }

    /**
     * @return string[]
     */
    public function allowedActions(): array
    {
        return $this->allowedActions;
    }

    /**
     * @param string $action
     * @return bool
     */
    public function isAllowedTo(string $action): bool
    {
        return in_array($action, $this->allowedActions());
    }

    /**
     * @return bool
     */
    public function isAdmin():bool
    {
        $isAdminUserType =  $this->userType() === 'admin';
        $hasAdminAction = $this->isAllowedTo('admin');
        return $isAdminUserType || $hasAdminAction;
    }
}
