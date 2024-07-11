<?php

namespace App\Controller;

use App\Dto\CreateProductDto;
use App\Dto\EditProductDto;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/products')]
class ProductController extends ApiController
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    #[IsGranted('ROLE_ADMIN', message: 'You are not permitted to perform this action'), IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('', name: 'create_product', methods: ['POST'])]
    public function create(
        #[MapRequestPayload()] CreateProductDto $createProductDto,
    ): JsonResponse {
        $product = $this->productService->create($createProductDto);

        return $this->transformResponse('Product created successfully', $product, JsonResponse::HTTP_CREATED);
    }

    #[IsGranted('ROLE_ADMIN', message: 'You are not permitted to perform this action'), IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{productId}', name: 'edit_product', methods: ['PUT'])]
    public function edit(
        int|string $productId,
        #[MapRequestPayload()] EditProductDto $editProductDto,
    ): JsonResponse {
        $product = $this->productService->edit($productId, $editProductDto);

        return $this->transformResponse('Product updated successfully', $product, JsonResponse::HTTP_OK);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{productId}', name: 'fetch_one_product', methods: ['GET'])]
    public function findOne(
        int|string $productId,
    ): JsonResponse {
        $product = $this->productService->findOne($productId);

        return $this->transformResponse('Product fetched successfully', $product);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('', name: 'fetch_all_product', methods: ['GET'])]
    public function findAll(): JsonResponse
    {
        $product = $this->productService->findAll();

        return $this->transformResponse('Products fetched successfully', $product);
    }

    #[IsGranted('ROLE_ADMIN', message: 'You are not permitted to perform this action'), IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{productId}', name: 'delete_product', methods: ['DELETE'])]
    public function remove(
        int|string $productId,
    ): JsonResponse {
        $product = $this->productService->remove($productId);

        return $this->transformResponse('Product deleted successfully', $product);
    }
}
