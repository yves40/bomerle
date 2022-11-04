<?php
namespace App\Services;

use App\Core\Mail;
use PHPMailer\PHPMailer\PHPMailer;

class MailTrap extends Mail 
{

  private PHPMailer $phpmailer;

  //----------------------------------------------------------------------
  public function __construct(string $to)
  {
    parent::__construct($to, __CLASS__);
  }
  //----------------------------------------------------------------------
  public function sendRegisterConfirmation(string $mailtext, $useremail) {

    // Get a token + selector object
    $tks = $this->createToken('/admin/registeruserconfirmed'); 
    // PHP mailer
    $this->phpmailer = new PHPMailer();
    $this->phpmailer->isSMTP();
    $this->phpmailer->Host = 'smtp.mailtrap.io';
    $this->phpmailer->SMTPAuth = true;
    $this->phpmailer->Port = 2525;
    $this->phpmailer->Username = '8173ffa6d214ac';
    $this->phpmailer->Password = '7267baab0b8650';

    $this->phpmailer->setFrom($this->from);
    $this->phpmailer->isHTML(true);
    $this->phpmailer->Subject = 'Registration confirmation';
    $this->phpmailer->Body = $this->buildMessage($mailtext, $tks);
    $this->phpmailer->addAddress($this->to);
    if($this->phpmailer->send()) 
    {
      // Memorize a request record used later for confirmation
      $this->storeMailRequest($useremail, $tks, 'register');
      return true;
    }
    else 
    {
      return false;
    }
  }
  //----------------------------------------------------------------------
  public function sendPasswordReset(string $subject, $userpseudo) {

    // Get a token + selector object
    $tks = $this->createToken('/admin/passwordresetconfirmed'); 
    // PHP mailer
    $this->phpmailer = new PHPMailer();
    $this->phpmailer->isSMTP();
    $this->phpmailer->Host = 'smtp.mailtrap.io';
    $this->phpmailer->SMTPAuth = true;
    $this->phpmailer->Port = 2525;
    $this->phpmailer->Username = '8173ffa6d214ac';
    $this->phpmailer->Password = '7267baab0b8650';

    $this->phpmailer->setFrom($this->from);
    $this->phpmailer->isHTML(true);
    $this->phpmailer->Subject = 'Reset password request confirmation';
    $this->phpmailer->Body = $this->buildMessage($subject, $tks);
    $this->phpmailer->addAddress($this->to);
    if($this->phpmailer->send()) 
    {
      $this->logger->db('email URL :'.$tks->getUrl());
      // Memorize a request record used later for confirmation
      $this->storeMailRequest($userpseudo, $tks, 'reset');
      return true;
    }
    else 
    {
      return false;
    }
  }
}

?>