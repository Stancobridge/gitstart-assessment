<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserDto
{
    #[
        Assert\NotBlank(message: 'Email is required'),
        Assert\Email(message: 'The email is not a valid email')
    ]
    public string $email;

    #[Assert\NotBlank(message: 'Fullname is required')]
    public string $fullName;

    #[Assert\NotBlank(message: 'Password is required')]
    public string $password;
}
