<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 3:58 PM
 */

namespace App\Projectors\Lead;

use App\Projectors\Projector;
use Rest\Sales\Domain\Model\Lead\Event\LeadPartiallySignedUpOnline;
use Rest\Shared\DomainEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PartiallySignedUpLeadRecordProjector implements Projector
{
    public function name(): string
    {
        return 'PartiallySignedUpLeadRecordProjector';
    }

    /***
     * @param DomainEvent $domainEvent
     * @return bool
     * @throws \Exception
     */
    public function project(DomainEvent $domainEvent): bool
    {
        if ($domainEvent instanceof LeadPartiallySignedUpOnline) {

            /**
             * @var $container ContainerInterface
             */
            $container = app()->get(ContainerInterface::class);
            $partiallySignedUpLeadsModel = $container->get('partially_signed_up_leads_model');

            $token = $this->generateToken($domainEvent);
            $partiallySignedUpLeadsModel->token = $token;
            $partiallySignedUpLeadsModel->code = $this->generatePinCode();
            $partiallySignedUpLeadsModel->lead_id = $domainEvent->leadId();
            $partiallySignedUpLeadsModel->email = $domainEvent->email();
            $partiallySignedUpLeadsModel->signedup_on = $domainEvent->happenedOn()->getTimestamp();
            return $partiallySignedUpLeadsModel->save();
        }

        return false;
    }

    /**
     * @param LeadPartiallySignedUpOnline $domainEvent
     * @return string
     */
    private function generateToken(LeadPartiallySignedUpOnline $domainEvent): string
    {
        return md5($domainEvent->leadId() . $domainEvent->email());
    }

    /**
     * @param int $digits
     * @return string
     */
    private function generatePinCode($digits = 4): string
    {
        if (env('APP_ENV') !== 'production') {
            return 1234;
        }
        $code = mt_rand(1, 9999);
        $paddedCode = str_pad($code, $digits, STR_PAD_LEFT);
        return $paddedCode;
    }
}
