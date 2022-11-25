<?php
namespace App\Services;

use App\Core\Mail;
use App\Core\Token;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailO2
{
  //----------------------------------------------------------------------
  public function __construct(private MailerInterface $mailer) { }
  //----------------------------------------------------------------------
  public function sendRegisterConfirmation(String $to) : Token {
    // Get a token + selector object
    $tks =  new Token('/robot/registeruserconfirmed'); 
    $message = $this->buildRegistrationMessage($_ENV['MAIL_REGISTER_SUBJECT'], $tks);
    // ------------------------------------------------------------------------
    // Send email: here we use the symfony standard package
    // Remember a worker (consumer) has to process the emails otherwise they 
    // won't be sent.
    // Can be launched with this command :  
    //                  php bin/console messenger:consume async -vv
    // or
    //                  php bin/console messenger:consume async
    // This should be automatically started in production with a cron job...
    // The consumer must be stopped properly with this command:
    //                  php bin/console messenger:stop
    // During the development phase, we send mails synchronously.
    // To do that we changed the messenger.yaml file located in config/packages
    // This line was modified. 
    //          Symfony\Component\Mailer\Messenger\SendEmailMessage: sync
    // In that case the above consumer does not hve to be launched ;-)
    // ------------------------------------------------------------------------
    $email = (new Email())
        ->from($_ENV["MAIL_FROM"])
        ->to($to)
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
  }
  //----------------------------------------------------------------------
  public function sendPasswordReset(string $to) {
        // Get a token + selector object
        $tks =  new Token('/robot/resetmypassword');
        $message = $this->buildPasswordResetMessage($_ENV['MAIL_PWDRESET_SUBJECT'], $tks);
        $email = (new Email())
            ->from($_ENV["MAIL_FROM"])
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($_ENV["MAIL_FROM"])
            //->priority(Email::PRIORITY_HIGH)
            ->subject($_ENV['MAIL_PWDRESET_SUBJECT'])
            ->html($message);
        try {
            $this->mailer->send($email);
            return $tks;
        } catch (TransportExceptionInterface $e) {
            return null;
        }            
  }
  //----------------------------------------------------------------------
  // Return true if the email has been properly sent
  //----------------------------------------------------------------------
  public function sendAdministratorInfo(string $to = null, $subject = 'No subject, probably a programming error', $message = 'No message, is it a test ?'): bool {
    if(!$to) {
      $to= $admin = ($_ENV['MAIL_ADMIN']);
    }
    $email = (new Email())
        ->from($_ENV["MAIL_FROM"])
        ->to($to)
        ->subject($subject)
        ->html($message);
    try {
        $this->mailer->send($email);
        return true;
    } catch (TransportExceptionInterface $e) {
        return false;
    }            
  }

  //----------------------------------------------------------------------
  private function buildRegistrationMessage(string $subject, Token $tks) 
  {
    $atlast = date('d-m-Y H:i',$tks->getExpires());
    date_default_timezone_set('Europe/Paris');
    $message = "<p>".$subject."</p>";
    $message .= "<br><br>";
    $message .= "<p>En confirmant cette demande vous acceptez le stockage sécurisé de votre email dans nos serveurs.</p>";
    $message .= "<br>";
    $message .= "<a href='".$tks->getUrl()."'>Confirmer ma demande</a>";
    $message .= "<br><br>";
    $message .= '<p>Avant le '.$atlast.'</p>';
    return $message;
  }  
  //----------------------------------------------------------------------
  private function buildPasswordResetMessage(string $subject, Token $tks) 
  {
    date_default_timezone_set('Europe/Paris');
    $message = "<p>".$subject."</p>";
    $message .= "<br>";
    $message .= "<a href='".$tks->getUrl()."'>Réinitialiser mon mot de passe</a>";
    $message .= "<br><br>";
    return $message;
  }
}

?>