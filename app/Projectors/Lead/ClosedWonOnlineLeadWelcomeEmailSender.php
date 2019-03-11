<?php
/**
 * Created by PhpStorm.
 * User: Erickson Reyes
 * Date: 13/12/2018
 * Time: 12:48 PM
 */

namespace App\Projectors\Lead;

use App\Projectors\Projector;
use App\Repositories\Query\Account;
use Rest\Sales\Domain\Model\Lead\Event\LeadCompletedOnlineSignUp;
use Rest\Shared\DomainEvent;
use Rest\Shared\Interfaces\Mailer\Email;
use Rest\Shared\Interfaces\Mailer\EmailAddress;
use Rest\Shared\Interfaces\Mailer\HtmlEmailBody;
use spec\PhpSpec\Exception\Fracture\NamedConstructorNotFoundExceptionSpec;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ClosedWonOnlineLeadWelcomeEmailSender implements Projector
{
    /**
     * @return string
     */
    public function name(): string
    {
        return 'ClosedWonOnlineLeadWelcomeEmailSender';
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     */
    public function project(DomainEvent $domainEvent): bool
    {
        if ($domainEvent instanceof LeadCompletedOnlineSignUp) {
            $container = app()->get(ContainerInterface::class);
            $accountsRepository = $container->get('accounts_repository');

            $trial = 0;
            while ($trial < 5) {
                $account = $accountsRepository->findById($domainEvent->accountId());
                if ($account instanceof Account) {
                    return $this->sendEmail($account);
                }
                sleep(1);
                $trial++;
            }
        }

        return false;
    }

    /**
     * @param Account $account
     * @return mixed
     */
    private function sendEmail(Account $account)
    {
        $container = app()->get(ContainerInterface::class);
        $senderName = $container->getParameter('knit_mail_sender_name');

        $body = new HtmlEmailBody("
                    <h1>You're here!</h1>
                    
                    <p>Hello!</p>
                    <p>
                      Thank you for your signing up to RestWare.
                    </p>
                    <p>Cheers!</p>
                    {$senderName}
                ");
        $subject = 'Welcome to RestWare!';
        $subject .= env('APP_ENV') !== 'production' ? ' (' .
            strtoupper(env('APP_ENV')) . ')' : '';

        $sender = new EmailAddress($container->getParameter('smtp_username'), $senderName);
        $recipient = new EmailAddress($account->email());

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
