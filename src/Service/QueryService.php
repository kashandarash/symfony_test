<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;

class QueryService {

  public function getHelloText(string $name): string {
    return 'Hello ' . $name . '!';
  }

}