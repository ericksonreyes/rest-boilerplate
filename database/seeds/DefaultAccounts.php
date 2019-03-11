<?php

use Illuminate\Database\Seeder;

class DefaultAccounts extends Seeder
{
    /**
     * @var \Faker\Generator
     */
    private $seeder;

    /**
     * @var string
     */
    private $adminUserId;

    public function __construct()
    {
        $this->adminUserId = env('INITIAL_ADMIN_USERID');
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        $leadId = uniqid('lead-', false);
        $accountId = 'sourcefit';
        $email = 'admin@sourcefit.com';
        $status = 'Active';
        $closedOn = new DateTimeImmutable();
        $closedBy = 'online';

        /**
         * Create Account Record
         */
        DB::table('sales_accounts')->insert([
            'account_id' => $accountId,
            'email' => $email,
            'status' => $status,
            'closed_on' => $closedOn->getTimestamp(),
            'closed_by' => $closedBy,
            'created_at' => $closedOn
        ]);

        /**
         * Create Lead Record
         */
        DB::table('sales_closed_won_leads')->insert([
            'lead_id' => $leadId,
            'account_id' => $accountId,
            'email' => $email,
            'closed_on' => $closedOn->getTimestamp()
        ]);
    }
}
