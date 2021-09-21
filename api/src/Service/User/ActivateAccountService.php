<?php
    
    namespace App\Service\User;
    
    use App\Repository\UserRepository;
    use Symfony\Component\HttpFoundation\Request;

    class ActivateAccountService{
        private UserRepository $userRepository;
    
        public function __construct(UserRepository $userRepository){
            $this->userRepository = $userRepository;
        }
    
        public function activate(Request $request): void{
        
        }
    }