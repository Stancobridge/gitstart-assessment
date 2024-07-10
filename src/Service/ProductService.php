<?php

namespace App\Service;

use App\Dto\CreateProductDto;
use App\Dto\EditProductDto;
use App\Entity\Product;
use App\Exception\InternalServerHttpException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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


  public function edit(int $id, EditProductDto $editProductDto)
  {
    try {

      $this->entityManager->beginTransaction();

      $product = $this->productRepository->find($id);

      $product->setName($editProductDto->name ?? $product->name);
      $product->setPrice((float)($editProductDto->price ?? $product->price));
      $product->setDescription($editProductDto->description ?? $product->description);

      $this->entityManager->flush();

      $this->entityManager->commit();

      return $product;
    } catch (\Exception $e) {
      $this->loggerInterface->error($e->getMessage());

      $this->entityManager->rollback();


      throw new InternalServerHttpException('Error occurred editing product, please try again or reach out to support');
    }
  }

  public function findOne($id)
  {
    $product = $this->productRepository->find($id);

    if (!$product) {
      throw new NotFoundHttpException('Product not found');
    }

    return $product;
  }

  public function findAll()
  {
    $product = $this->productRepository->findAll();

    if (!$product) {
      throw new NotFoundHttpException('Product not found');
    }

    return $product;
  }

  public function remove($id)
  {
    $product = $this->findOne($id);

    $this->entityManager->remove($product);
    $this->entityManager->flush();

    return true;
  }
}
