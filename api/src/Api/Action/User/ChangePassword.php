<?php
    
    namespace App\Api\Action\User;
    
    use App\Entity\User;
    use App\Service\Request\RequestService;
    use App\Service\User\ChangePasswordService;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use Symfony\Component\HttpFoundation\Request;
    
    class ChangePassword{
        private ChangePasswordService $service;
        
        public function __construct(ChangePasswordService $service){
            $this->service = $service;
        }
    
        /**
         * @throws OptimisticLockException
         * @throws ORMException
         */
        public function __invoke(Request $request, User $user): User{
            return $this->service->changePassword(
                $user->getId(),
                RequestService::getField($request, 'oldPassword'),
                RequestService::getField($request, 'newPassword'));
        }
    }