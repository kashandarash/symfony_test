<?php

namespace App\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;


class TestForm {

  #[Assert\NotBlank(message: 'Field One is totally required', groups: ['step1'])]
  public ?string $field_one = null;
  public ?string $field_two = null;

  #[Assert\Email(message: 'Field One is totally required', groups: ['step1'])]
  public ?string $email = null;


  #[Assert\Regex(pattern: '/\?/', message: 'Message do not have ? symbol', groups: ['step2'])]
  public ?string $message = null;

}