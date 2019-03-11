<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 13/12/2018
 * Time: 9:48 AM
 */

namespace App\Projectors\Lead;

use App\Projectors\Projector;
use App\Repositories\Query\PartiallySignedUpLead;
use Rest\Sales\Application\User\RecruitEmployee;
use Rest\Sales\Application\User\Handler\RecruitEmployeeHandler;
use Rest\Sales\Domain\Model\Lead\Event\LeadCompletedOnlineSignUp;
use Rest\Shared\DomainEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClosedWonOnlineLeadsProjector implements Projector
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'ClosedWonOnlineLeadsProjector';
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     * @throws \Exception
     */
    public function project(DomainEvent $domainEvent): bool
    {
        if ($domainEvent instanceof LeadCompletedOnlineSignUp) {
            /**
             * @var $container ContainerInterface
             */
            $container = app()->get(ContainerInterface::class);
            $partiallySignedUpLeadsRepository = $container->get('partially_signed_up_leads_repository');

            /**
             * @var PartiallySignedUpLead $latestPartiallySignedUpLead
             */
            $partiallySignedUpLeads = $partiallySignedUpLeadsRepository->findAllByLeadId($domainEvent->leadId());

            $latestPartiallySignedUpLead = null;
            foreach ($partiallySignedUpLeads as $partiallySignedUpLead) {
                $latestPartiallySignedUpLead = $partiallySignedUpLead;
            }

            if ($latestPartiallySignedUpLead instanceof PartiallySignedUpLead) {
                $closedWonLeadsModel = $container->get('sales_closed_won_leads_model');
                $closedWonLeadsModel->lead_id = $domainEvent->leadId();
                $closedWonLeadsModel->account_id = $domainEvent->accountId();
                $closedWonLeadsModel->email = $latestPartiallySignedUpLead->email();
                $closedWonLeadsModel->closed_on = $domainEvent->happenedOn()->getTimestamp();
                $closedWonLeadsModel->save();

                $accountsModel = $container->get('accounts_model');
                $accountsModel->account_id = $domainEvent->accountId();
                $accountsModel->email = $latestPartiallySignedUpLead->email();
                $accountsModel->status = 'Active';
                $accountsModel->closed_on = $domainEvent->happenedOn()->getTimestamp();
                $accountsModel->closed_by = 'Online';
                $accountsModel->save();

                $container->get('partially_signed_up_leads_repository')->markLeadAsClosed($latestPartiallySignedUpLead);

                $convertLeadToUserRequest = new RecruitEmployee(
                    $container->get('identity_generator')->nextIdentity('user-'),
                    $closedWonLeadsModel->lead_id
                );
                $convertLeadToUserHandler = new RecruitEmployeeHandler(
                    $container->get('lead_repository'),
                    $container->get('user_repository')
                );
                $convertLeadToUserHandler->handleThis($convertLeadToUserRequest);
                return true;
            }
        }

        return false;
    }
}
