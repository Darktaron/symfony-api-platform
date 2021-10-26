<?php
    
    namespace App\Api\Action\User;
    
    use App\Service\User\RequestResetPasswordService;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    
    class RequestResetPassword{
        private RequestResetPasswordService $service;
        
        public function __construct(RequestResetPasswordService $service){
            $this->service = $service;
        }
    
        /**
         * @throws OptimisticLockException
         * @throws ORMException
         */
        public function __invoke(Request $request): JsonResponse{
            $this->service->send($request);
            return new JsonResponse(['message' => 'Request reset password sent']);
        }
    }