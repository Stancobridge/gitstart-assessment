<?php

namespace App\Service;

use App\Dto\CreateProductDto;
use App\Entity\Product;
use App\Exception\InternalServerHttpException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProductService
{
  public function __construct(
    private readonly ProductRepository $productRepository,
    private readonly EntityManagerInterface $entityManager,
    private readonly LoggerInterface $loggerInterface,
    private readonly string $uploadDirectory
  ) {
  }

  public function create(CreateProductDto $createProductDto, UploadedFile $productPhoto)
  {
    try {

      $this->entityManager->beginTransaction();

      $fileName = uniqid() . '.' . $productPhoto->guessExtension();
      $photoDir = '/products';

      $productPhoto->move(
        $this->uploadDirectory . $photoDir,
        $fileName
      );

      $product = new Product();

      $product->setName($createProductDto->name);
      $product->setPrice((float)$createProductDto->price);
      $product->setDescription($createProductDto->description);
      $product->setImageUrl($photoDir . '/' . $fileName);

      $this->entityManager->persist($product);
      $this->entityManager->flush();

      $this->entityManager->commit();

      return $product;
    } catch (\Exception $e) {
      $this->loggerInterface->error($e->getMessage());

      $this->entityManager->rollback();


      throw new InternalServerHttpException('Error occurred creating product, please try again or reach out to support');
    }
  }
}
