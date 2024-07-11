<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductDto
{
    #[
        Assert\NotBlank(message: 'Name is required'),
    ]
    public string $name;

    #[
        Assert\NotBlank(message: 'Price is required'),
        Assert\Type(message: 'Price must be a number', type: 'numeric'),
        Assert\Positive(message: 'Price must be a positive number'),
    ]
    public float $price;

    #[Assert\NotBlank(message: 'Description is required')]
    public string $description;
}
