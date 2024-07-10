<?php

namespace App\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductPhotoDto
{

  #[

    Assert\Image(
      maxSize: '4096k'
      /** 4MB */
      ,
      maxSizeMessage: 'Product photo size must not exceed 4mb'
    )
  ]
  public ?UploadedFile $photo = null;
}
