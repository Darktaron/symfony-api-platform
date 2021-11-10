<?php

namespace App\Security\Core\User;

    use App\Entity\User;
    use App\Exception\User\UserNotFoundException;
    use App\Repository\UserRepository;
    use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
    use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
    use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Security\Core\User\UserProviderInterface;

    class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
    {
        private UserRepository $repository;

        public function __construct(UserRepository $repository)
        {
            $this->repository = $repository;
        }

        public function loadUserByUsername(string $username): UserInterface
        {
            try {
                return $this->repository->findOneByEmailOrFail($username);
            } catch (UserNotFoundException $e) {
                throw new UsernameNotFoundException(\sprintf('User %s not found', $username));
            }
        }

        public function refreshUser(UserInterface $user): UserInterface
        {
            if (!$user instanceof User) {
                throw new UnsupportedUserException(\sprintf('instance of %s are not supported', \get_class($user)));
            }

            return $this->loadUserByUsername($user->getUsername());
        }

        public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
        {
            $user->setPassword($newEncodedPassword);
            $this->repository->save($user);
        }

        public function supportsClass(string $class)
        {
            return User::class === $class;
        }
    }
