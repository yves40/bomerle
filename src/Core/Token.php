<?php

namespace App\Core;

class Token {

  private $selector;
  private $token;
  // private $hashedtoken; 
  private $expires;
  private $url;

  /**
   * TARGETURL est le path dans le routeur pour confirmer l'enregistrement
   * Appelé lorsque l'utilisateur accepte l'action en cliquant dans son mail
   */
  public function __construct($targetUrl)
  {
    $host = $_SERVER['SERVER_NAME'];    // To build the URL
    // selector is used to find the user in the resets table
    $this->selector = bin2hex(random_bytes(8));
    // token is used to check the request is safe
    $this->token = bin2hex(random_bytes(32));
    date_default_timezone_set('Europe/Paris');
    $this->expires = date("U") + 1800; 
    $this->url = 'http://'.$host.$targetUrl;
    $this->url .= '/'.$this->selector.'/'.$this->token;
  }
  // --------------------------------------------------------------------------
  public function getSelector() {
    return $this->selector;
  }
  public function getToken() {
    return $this->token;
  }
  public function getUrl() {
    return $this->url;
  }
  public function getExpires() {
    return $this->expires;
  }
}

?>