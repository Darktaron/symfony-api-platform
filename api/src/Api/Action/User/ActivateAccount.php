<?php
    
    namespace App\Api\Action\User;
    
    use App\Entity\User;
    use App\Service\Request\RequestService;
    use App\Service\User\ActivateAccountService;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use Symfony\Component\HttpFoundation\Request;
    
    class ActivateAccount{
        private ActivateAccountService $service;
        
        public function __construct(ActivateAccountService $service){
            $this->service = $service;
        }
    
        /**
         * @throws OptimisticLockException
         * @throws ORMException
         */
        public function __invoke(Request $request, string $id): User{
            return $this->service->activate($id, RequestService::getField($request, 'token'));
        }
    }