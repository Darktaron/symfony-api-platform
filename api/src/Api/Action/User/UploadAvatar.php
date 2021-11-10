<?php
    
    declare(strict_types=1);
    
    
    namespace App\Api\Action\User;
    
    use App\Entity\User;
    use App\Service\User\UploadAvatarService;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use League\Flysystem\FilesystemException;
    use Symfony\Component\HttpFoundation\Request;

    class UploadAvatar{
        private UploadAvatarService $avatarService;
    
        public function __construct(UploadAvatarService $avatarService){
        
            $this->avatarService = $avatarService;
        }
    
        /**
         * @throws OptimisticLockException
         * @throws FilesystemException
         * @throws ORMException
         */
        public function __invoke(Request $request, User $user): User{
            return $this->avatarService->uploadAvatar($request, $user);
        }
    }