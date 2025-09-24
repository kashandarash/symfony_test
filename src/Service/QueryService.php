<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;

class QueryService {

//  public function __construct(
//    private UserRepository $userRepository,
//  ) {}
//
//  public function findUser(int $authorId): ?User {
//    return $this->userRepository->find($authorId);
//  }
//
//  public function getAllUsers(): array {
//    return $this->userRepository->findAll();
//  }

  public function getHelloText(): string {
    return 'Hello World!';
  }

}