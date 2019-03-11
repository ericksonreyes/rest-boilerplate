<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 10/12/2018
 * Time: 6:01 PM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception\AccountNotFoundException;
use App\Repositories\Query\Account;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountsController extends Controller
{

    /**
     * @param string $accountId
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws AccountNotFoundException
     * @throws \ReflectionException
     */
    public function get(string $accountId)
    {
        try {
            $repository = $this->container()->get('accounts_repository');
            $account = $repository->findById($accountId);

            if ($account instanceof Account === false) {
                throw new AccountNotFoundException();
            }

            $responseArray = [
                'accountId' => $accountId,
                'email' => (string) $account->email(),
                'createdOn' => $account->createdOn(),
                'closedBy' => $account->closedBy(),
                'closedOn' => $account->closedOn(),
                '_links' => [
                    [
                        "rel" => "self",
                        "href" => url("/" . env('APP_VERSION'). "/sales/api/accounts/{$accountId}"),
                        "type" => "GET",
                        "title" => "View"
                    ]
                ]
            ];
            return $this->response($responseArray);
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }
}
