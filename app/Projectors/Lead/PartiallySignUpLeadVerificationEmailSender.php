<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 11/12/2018
 * Time: 4:38 PM
 */

namespace App\Projectors\Lead;

use App\Projectors\Projector;
use App\Repositories\Query\PartiallySignedUpLead;
use Rest\Sales\Domain\Model\Lead\Event\LeadPartiallySignedUpOnline;
use Rest\Shared\DomainEvent;
use Rest\Shared\Interfaces\Mailer\Email;
use Rest\Shared\Interfaces\Mailer\EmailAddress;
use Rest\Shared\Interfaces\Mailer\HtmlEmailBody;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PartiallySignUpLeadVerificationEmailSender implements Projector
{

    /**
     * @return string
     */
    public function name(): string
    {
        return 'PartiallySignUpLeadVerificationEmailSender';
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     */
    public function project(DomainEvent $domainEvent): bool
    {
        if ($domainEvent instanceof LeadPartiallySignedUpOnline) {
            $container = app()->get(ContainerInterface::class);
            $partiallySignedUpLeadsModel = $container->get('partially_signed_up_leads_model');
            $leadId = $domainEvent->leadId();


            $trial = 0;
            while ($trial < 5) {
                $partiallySignedUpLeads = $partiallySignedUpLeadsModel->findAllByLeadId($leadId);

                $latestPartiallySignedUpLead = null;
                foreach ($partiallySignedUpLeads as $partiallySignedUpLead) {
                    $latestPartiallySignedUpLead = $partiallySignedUpLead;
                }

                if ($latestPartiallySignedUpLead instanceof PartiallySignedUpLead) {
                    return $this->sendEmail($domainEvent, $latestPartiallySignedUpLead, $leadId);
                }
                $trial++;
            }
        }

        return false;
    }

    /**
     * @param DomainEvent $domainEvent
     * @param PartiallySignedUpLead|null $latestPartiallySignedUpLead
     * @param string $leadId
     * @return mixed
     */
    private function sendEmail(
        DomainEvent $domainEvent,
        PartiallySignedUpLead $latestPartiallySignedUpLead,
        string $leadId
    ): bool {
        $container = app()->get(ContainerInterface::class);
        $token = $latestPartiallySignedUpLead->token();
        $code = $latestPartiallySignedUpLead->code();
        $completionLink = $container->getParameter('knit_web_url');
        $completionLink .= "/verify-signup?id={$leadId}&token={$token}&code={$code}";
        $senderName = $container->getParameter('knit_mail_sender_name');
        $body = new HtmlEmailBody("
                    <h1>You're almost there!</h1>
                    
                    <p>Hello!</p>
                    <p>
                      Thank you for your interest in RestWare. 
                      To complete your sign up go <a href=\"{$completionLink}\" target='_blank'>here</a>.
                    </p>
                    <p>Or enter this confirmation code: {$code}</p>
                    <p>Cheers!</p>
                    {$senderName}
                ");
        $subject = 'Verify your RestWare sign up!';
        $subject .= env('APP_ENV') !== 'production' ? ' (' .
            strtoupper(env('APP_ENV')) . ')' : '';

        $sender = new EmailAddress($container->getParameter('smtp_username'), $senderName);
        $recipient = new EmailAddress($domainEvent->email());

        $email = new Email($sender, $recipient, $body);
        $email->setSubject($subject);

        $bccRecipients = $container->getParameter('knit_mail_bcc_recipients');
        foreach ($bccRecipients as $bccRecipient) {
            $email->addBcc(new EmailAddress($bccRecipient));
        }

        $emailTransport = $container->get('email_transport');
        $emailTransport->setUsername($sender->emailAddress());
        $emailTransport->setPassword($container->getParameter('smtp_password'));
        return $emailTransport->send($email);
    }
}
