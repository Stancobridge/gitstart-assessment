<?php

namespace App\Service\Auth;

use App\Dto\RegisterUserDto;
use App\Entity\User;
use App\Enum\Role;
use App\Exception\InternalServerHttpException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
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

    /**
     * Returns an array with 'auth_token' and 'user' keys.
     *
     * @return array{auth_token: string, user: User} an  array with 'auth_token' and 'user' of the registered user
     */
    public function createUser(RegisterUserDto $userDto): array
    {
        $this->throwIfUserAlreadyExists($userDto->email);

        $this->entityManager->beginTransaction();

        $user = new User();
        $authToken = null;

        try {
            $password = $userDto->password;

            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);

            $user->setEmail($userDto->email);
            $user->setFullName($userDto->fullName);
            $user->setPassword($hashedPassword);

            // check if admin already exist
            $adminUser = $this->userRepository->findOneBy([]);

            if (!$adminUser) {
                $user->setRoles([Role::ROLE_ADMIN]);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // create token
            $authToken = $this->jWTTokenManager->create($user);

            $this->entityManager->commit();
        } catch (\Exception $e) {
            // rollback registered user
            $this->entityManager->rollback();
            $this->loggerInterface->error($e->getMessage());

            throw new InternalServerHttpException('An error occurred while creating your account, try again or contact support');
        }

        return ['auth_token' => $authToken, 'user' => $user];
    }

    private function throwIfUserAlreadyExists(string $userEmail): void
    {
        // make sure user with same email does not exist
        $oldUser = $this->userRepository->findOneBy(['email' => $userEmail]);

        if ($oldUser) {
            throw new ConflictHttpException('User with same email already exist');
        }
    }
}
