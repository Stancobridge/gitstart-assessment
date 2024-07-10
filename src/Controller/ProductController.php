<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Dto\CreateProductDto;
use App\Enum\Role;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/products')]
class ProductController extends ApiController
{


  #[IsGranted('ADMIN'), IsGranted('IS_AUTHENTICATED_FULLY')]
  #[Route('/', methods: ['HEAD', 'POST'])]
  public function create(
    // #[MapRequestPayload()] CreateProductDto $createProductDto,
  ): JsonResponse
  {

    return $this->transformResponse('Product created successfully', []);
  }
}
