<?php
namespace App\Services;

use App\Core\Mail;
use App\Core\Token;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailO2 extends Mail 
{

  //----------------------------------------------------------------------
  public function __construct(private MailerInterface $mailer)
  {
    parent::__construct();
  }
  //----------------------------------------------------------------------
  public function sendRegisterConfirmation(String $to) : Token {
    $this->setTo($to);
    // Get a token + selector object
    $tks = $this->createToken('/admin/registeruserconfirmed'); 
    $message = $this->buildMessage($_ENV['MAIL_REGISTER_SUBJECT'], $tks);
    // ------------------------------------------------------------------------
    // Send email
    // Remember a worker has to consume the emails otherwise they won't be sent
    // Can be launched with this command :  
    //                  php bin/console messenger:consume async -vv
    // or
    //                  php bin/console messenger:consume async
    // This should be automatically started in production with a cron job...
    // The consumer must be stopped properly with this command:
    //                  php bin/console messenger:stop
    // ------------------------------------------------------------------------
    // dd($_ENV["MAIL_USER"]);
    $email = (new Email())
        ->from($_ENV["MAIL_FROM"])
        ->to($this->getTo())
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        ->replyTo($_ENV["MAIL_FROM"])
        //->priority(Email::PRIORITY_HIGH)
        ->subject($_ENV['MAIL_REGISTER_SUBJECT'])
        ->html($message);
    try {
        $this->mailer->send($email);
        return $tks;
    } catch (TransportExceptionInterface $e) {
        return null;
    }            
    // ------------------------------------------------------------------------

  }
  //----------------------------------------------------------------------
  public function sendPasswordReset(string $subject, $userpseudo) {

  }
}

?>