<?php

namespace App\Controller\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
  public function __construct(
    #[Assert\Email(message: 'The email must {{value}} is not a valid email')]
    public string $email,
    #[Assert\NotBlank(message: 'Fullname is required')]
    public string $fullName,
    #[Assert\NotBlank(message: 'Password is required')]
    public string $password
  ) {
  }
}
