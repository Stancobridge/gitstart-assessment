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
    Assert\Positive(message: 'Price must be a positive number'),
  ]
  public string $price;

  #[Assert\NotBlank(message: 'Description is required')]
  public string $description;


  #[Assert\Image(message: 'Image is required', maxSize: '4096k' /** 4MB */)]
  public string $photo;
}
