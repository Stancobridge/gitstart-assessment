<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EditProductDto
{
    #[
        Assert\Type(message: 'Name is required', type: 'string'),
    ]
    public ?string $name = null;

    #[
        Assert\Type(message: 'Price must be a number', type: 'numeric'),
        Assert\Positive(message: 'Price must be a positive number'),
    ]
    public ?float $price = null;

    #[Assert\Type(message: 'Description is required', type: 'string')]
    public ?string $description = null;
}
