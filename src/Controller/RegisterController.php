<?php

namespace App\Controller;

use App\Controller\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegisterController extends AbstractController
{
  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly EntityManagerInterface $entityManager,
    private readonly UserPasswordHasherInterface $userPasswordHasher,
    private readonly JWTTokenManagerInterface $jWTTokenManager,
    private readonly LoggerInterface $loggerInterface
  ) {
  }


  #[Route('/register', name: 'register', methods: ['HEAD', 'POST'])]
  public function createUser(
    #[MapRequestPayload] UserDto $userDto
  ): JsonResponse {
    // todo: add validation

    $user = new User();
    try {


      $password = $userDto->password;

      $hashedPassword  = $this->userPasswordHasher->hashPassword($user, $password);


      $user->setEmail($userDto->email);
      $user->setFullName($userDto->fullName);
      $user->setPassword($hashedPassword);

      $this->entityManager->persist($user);
      $this->entityManager->flush();
    } catch (Exception $e) {
      $this->loggerInterface->error($e->getMessage());
      throw new \Exception('An error occurred while creating your account, try again or contact support');
    }

    // create token
    $token = $this->jWTTokenManager->create($user);

    return $this->json(['token' => $token]);
  }


  // #[IsGranted('IS_AUTHENTICATED_FULLY')]
  #[Route('/api/products', methods: ['GET'])]
  public function authRoute(): JsonResponse
  {

    return $this->json([]);
  }
}
