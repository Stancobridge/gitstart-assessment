<?php

namespace App\Controller;

use App\Dto\RegisterUserDto;

use App\Service\Auth\RegistrationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class RegistrationController extends ApiController
{
  public function __construct(
    private readonly RegistrationService $registrationService,
  ) {
  }


  #[Route('/register', name: 'register', methods: ['POST'])]
  public function createUser(
    #[MapRequestPayload] RegisterUserDto $registerUserDto,
  ): JsonResponse {

    $registeredUser = $this->registrationService->createUser($registerUserDto);

    return $this->transformResponse('Account created successfully', $registeredUser, JsonResponse::HTTP_CREATED);
  }
}
