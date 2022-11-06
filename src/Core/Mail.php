<?php
namespace App\Core;

use DateTime;
use App\Core\Token;
use App\Entity\RequestsTracker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Gère l'envoi, la création du token
 * Contruit le message envoyé
 * Stocke dans la bdd la mailrequest
 */
abstract class Mail 
{
  protected $to;
  //----------------------------------------------------------------------
  public function __construct() { }  
  //----------------------------------------------------------------------
  abstract protected function sendRegisterConfirmation(String $to);
  //----------------------------------------------------------------------
  public function createToken($url)  { return new Token($url); }  
  //----------------------------------------------------------------------
  public function setTo($to)  { $this->to = $to; }  
  public function getTo() { return $this->to; }
  //----------------------------------------------------------------------
  public function buildMessage(string $subject, Token $tks) 
  {
    $atlast = date('d-m-Y h:i',$tks->getExpires());
    date_default_timezone_set('Europe/Paris');
    $message = "<p>".$subject."</p>";
    $message .= "<p>Click on this link to confirm</p>";
    $message .= "<a href='".$tks->getUrl()."'>".$tks->getUrl()."</a>";
    $message .= '<p>Proceed before '.$atlast.'</p>';
    return $message;
  }
  // ----------------------------------------------------------------------  
  public function storeMailRequest($email, $tks, $actionType) {
    // date_default_timezone_set('Europe/Paris');
    // $expires = date("U") + 1800; // 30 minutes delay before expiration
    // $rqtracker = new RequestsTracker();
    // $rqtracker->setRequestactiontype($actionType);
    // $rqtracker->setEmail($email);
    // $rqtracker->setCreated(new DateTime('now'));
    // $rqtracker->setProcessed(new DateTime('now'));
    // $rqtracker->setExpires($expires);
    // $rqtracker->setToken($tks);
    // $rqtracker->setSelector($tks->getSelector());
    // $rqtracker->setStatus(self::STATUS_REQUESTED);
    //
    // How to get the RequestsTrackerRepository from here ??
    //
    // $rqtracker->persist($rqtracker);
    // $rqtracker->flush();
  }
}

?>