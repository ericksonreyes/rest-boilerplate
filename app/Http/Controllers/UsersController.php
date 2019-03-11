<?php
/**
 * Created by PhpStorm.
 * User: ericksonreyes
 * Date: 2019-01-03
 * Time: 20:13
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Exception\DeletedUserException;
use App\Http\Controllers\Exception\UserNotFoundException;
use Exception;
use Rest\Sales\Domain\Model\User\Employee;

class UsersController extends Controller
{

    /**
     * @param string $userId
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \ReflectionException
     */
    public function getUser(string $userId)
    {
        try {
            $repository = $this->container()->get('user_repository');

            $user = $repository->findById($userId);


            if ($user instanceof Employee === false) {
                throw new UserNotFoundException();
            }

            if ($user->isDeleted()) {
                throw new DeletedUserException();
            }


            $responseArray = [
                'userId' => $user->id(),
                'leadId' => $user->leadId(),
                'accountId' => $user->accountId(),
                'email' => $user->email(),
                '_links' => [
                    'rel' => 'self',
                    'href' => url('/' . env('APP_VERSION') . "/sales/api/users/{$userId}"),
                    'type' => 'GET',
                    'title' => 'View'
                ]
            ];

            return $this->response(
                $responseArray,
                200
            );
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }
}
