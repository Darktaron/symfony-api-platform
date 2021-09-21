<?php
    
    namespace App\Api\Action\User;
    
    use App\Entity\User;
    use App\Service\User\ActivateAccountService;
    use Symfony\Component\HttpFoundation\Request;
    
    class ActivateAccount{
        private ActivateAccountService $service;
        
        public function __construct(ActivateAccountService $service){
            $this->service = $service;
        }
        
        public function __invoke(Request $request, string $id): User{
            return $this->service->activate($request, $id);
        }
    }