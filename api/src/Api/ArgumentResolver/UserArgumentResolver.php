<?php

namespace App\Api\ArgumentResolver;

    use App\Entity\User;
    use App\Repository\UserRepository;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
    use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

    class UserArgumentResolver implements ArgumentValueResolverInterface
    {
        private TokenStorageInterface $tokenStorage;
        private UserRepository        $repository;

        public function __construct(TokenStorageInterface $tokenStorage, UserRepository $repository)
        {
            $this->tokenStorage = $tokenStorage;
            $this->repository = $repository;
        }

        public function supports(Request $request, ArgumentMetadata $argument): bool
        {
            if (User::class !== $argument->getType()) {
                return false;
            }

            $token = $this->tokenStorage->getToken();
            if (!$token instanceof TokenInterface) {
                return false;
            }

            return $token->getUser() instanceof User;
        }

        public function resolve(Request $request, ArgumentMetadata $argument): \Generator
        {
            yield $this->repository->findOneByEmailOrFail($this->tokenStorage->getToken()->getUser()->getEmail());
        }
    }
