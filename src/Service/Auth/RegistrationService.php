<?php

namespace App\Service\Auth;

use App\Dto\RegisterUserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly EntityManagerInterface $entityManager,
    private readonly UserPasswordHasherInterface $userPasswordHasher,
    private readonly JWTTokenManagerInterface $jWTTokenManager,
    private readonly LoggerInterface $loggerInterface
  ) {
  }


  public function createUser(RegisterUserDto $userDto)
  {
    $this->entityManager->beginTransaction();

    $user = new User();
    $authToken = null;

    try {


      $password = $userDto->password;

      $hashedPassword  = $this->userPasswordHasher->hashPassword($user, $password);

      $user->setEmail($userDto->email);
      $user->setFullName($userDto->fullName);
      $user->setPassword($hashedPassword);

      $this->entityManager->persist($user);
      $this->entityManager->flush();

      // create token
      $authToken = $this->jWTTokenManager->create($user);

      $this->entityManager->commit();
    } catch (\Exception $e) {
      // rollback registered user
      $this->entityManager->rollback();
      $this->loggerInterface->error($e->getMessage());

      throw new \Exception('An error occurred while creating your account, try again or contact support');
    }


    return ['auth_token' => $authToken, 'user' => $user];
  }
}
