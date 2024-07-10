<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Dto\CreateProductDto;
use App\Dto\ProductPhotoDto;
use App\Exception\ValidationHttpException;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/products')]
class ProductController extends ApiController
{

  /**
   * __construct
   *
   * @param  mixed $productService
   * @param  mixed $validatorInterface
   * @return void
   */
  public function __construct(private readonly ProductService $productService, private readonly ValidatorInterface $validatorInterface)
  {
  }


  #[IsGranted('ROLE_ADMIN', message: 'You are not permitted to perform this action'), IsGranted('IS_AUTHENTICATED_FULLY')]
  #[Route('', methods: ['HEAD', 'POST'])]
  public function create(
    #[MapRequestPayload()] CreateProductDto $createProductDto,
    Request $request
  ): JsonResponse {
    $productPhoto = $request->files->get('photo');
    $fileDto = new ProductPhotoDto();

    $fileDto->photo = $productPhoto;
    $errors = $this->validatorInterface->validate($fileDto);

    if (count($errors) > 0) {
      $errorMessages = [];
      foreach ($errors as $error) {
        $errorMessages[] = $error->getMessage();
      }
      throw new ValidationHttpException($errorMessages);
    }

    $product = $this->productService->create($createProductDto, $fileDto->photo);
    return $this->transformResponse('Product created successfully', $product);
  }
}
