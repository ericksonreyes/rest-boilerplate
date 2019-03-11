<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 13/12/2018
 * Time: 7:40 AM
 */

namespace App\Models\Query;

use App\Repositories\Query\Account;
use App\Repositories\Query\AccountDTO;
use App\Repositories\Query\AccountsRepository;
use Illuminate\Database\Eloquent\Model;
use Rest\Sales\Application\Lead\Service\AccountIdAvailabilityService;

class EloquentAccounts extends Model implements AccountIdAvailabilityService, AccountsRepository
{
    /**
     * @var string
     */
    protected $table = 'sales_accounts';

    /**
     * @param string $accountId
     * @return bool
     */
    public function isAccountIdAlreadyInUse(string $accountId): bool
    {
        return $this->where('account_id', $accountId)->count() > 1;
    }

    /**
     * @param string $accountId
     * @return Account
     */
    public function findById(string $accountId): ?Account
    {
        $model = $this->where('account_id', $accountId)->first();
        if ($model) {
            $account = new AccountDTO(
                $model->account_id,
                $model->email,
                $model->created_at->getTimestamp()
            );
            $account->setClosedOn($model->closed_on);
            $account->setClosedBy($model->closed_by);
            return $account;
        }

        return null;
    }
}
