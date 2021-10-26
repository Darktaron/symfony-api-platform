<?php
    
    namespace App\Service\User;
    
    use App\Entity\User;
    use App\Exception\Password\PasswordException;
    use App\Repository\UserRepository;
    use App\Service\Password\EncoderService;
    use App\Service\Request\RequestService;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use Symfony\Component\HttpFoundation\Request;

    class ChangePasswordService{
        private UserRepository $repository;
        private EncoderService $service;
    
        public function __construct(UserRepository $repository, EncoderService $service){
            $this->repository = $repository;
            $this->service = $service;
        }
    
        /**
         * @throws OptimisticLockException
         * @throws ORMException
         */
        public function changePassword(Request $request, User $user): User{
            $oldPassword = RequestService::getField($request, 'oldPassword');
            $newPassword = RequestService::getField($request, 'newPassword');
            
            if(!$this->service->isValidPassword($user, $oldPassword)){
                throw PasswordException::oldPasswordDoesNotMatch();
            }
            
            $user->setPassword($this->service->generateEncodedPassword($user, $newPassword));
            $this->repository->save($user);
            
            return $user;
        }
    }