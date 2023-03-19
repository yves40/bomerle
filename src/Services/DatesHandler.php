<?php

namespace App\Services;

use DateTime;

class DatesHandler
{
  private  DateTime $startDate;
  private  DateTime $endDate;

  function __construct (){
    date_default_timezone_set('Europe/Paris');
    $this->startDate = new \DateTime('now');    
    $this->endDate = new \DateTime('now');    
  }

  function setStartDate(DateTime $date) {
    $this->startDate = $date;
  }
  function setEndDate(DateTime $date) {
    $this->endDate = $date;
  }
  function getEndDate() : string { return $this->endDate->format('d/m/y'); }
  function getStartDate(): string { return $this->startDate->format('d/m/y'); }
}