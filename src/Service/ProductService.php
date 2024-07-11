<?php

namespace App\Service;

use App\Dto\CreateProductDto;
use App\Dto\EditProductDto;
use App\Entity\Product;
use App\Exception\InternalServerHttpException;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $loggerInterface,
    ) {
    }

    public function create(CreateProductDto $createProductDto): Product
    {
        try {
            $this->entityManager->beginTransaction();

            $product = new Product();

            $product->setName($createProductDto->name);
            $product->setPrice((float) $createProductDto->price);
            $product->setDescription($createProductDto->description);

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

    public function edit(int|string $id, EditProductDto $editProductDto): Product
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        try {
            $this->entityManager->beginTransaction();

            $product->setName((string) ($editProductDto->name ?? $product->name));
            $product->setPrice((float) ($editProductDto->price ?? $product->price));
            $product->setDescription((string) ($editProductDto->description ?? $product->description));

            $this->entityManager->flush();

            $this->entityManager->commit();

            return $product;
        } catch (\Exception $e) {
            $this->loggerInterface->error($e->getMessage());

            $this->entityManager->rollback();

            throw new InternalServerHttpException('Error occurred editing product, please try again or reach out to support');
        }
    }

    public function findOne(int|string $id): Product
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        return $product;
    }

    /**
     * Returns an array of Product objects.
     *
     * @return Product[] an array of Product objects
     */
    public function findAll(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * remove.
     */
    public function remove(int|string $id): bool
    {
        $product = $this->findOne($id);

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return true;
    }
}
