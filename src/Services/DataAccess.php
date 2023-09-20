<?php
namespace App\Services;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\Persistence\ManagerRegistry;

class DataAccess
{
    // -------------------------------------------------------------------------
    public function __construct(private ManagerRegistry $mgr) { }
    // -------------------------------------------------------------------------
    public function getRepository($classname): ObjectRepository {
      return $this->mgr->getRepository($classname);
    }
}
