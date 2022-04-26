<?php
    
    declare(strict_types=1);
    
    
    namespace App\Service\User;
    
    
    use App\Entity\User;
    use App\Repository\UserRepository;
    use App\Service\File\FileService;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use League\Flysystem\FilesystemException;
    use Symfony\Component\HttpFoundation\Request;
    
    class UploadAvatarService{
        private UserRepository $repository;
        private FileService    $fileService;
        private string         $mediaPath;
    
        public function __construct(UserRepository $repository, FileService $fileService, string $mediaPath){
            $this->repository = $repository;
            $this->fileService = $fileService;
            $this->mediaPath = $mediaPath;
        }
    
        /**
         * @throws OptimisticLockException
         * @throws FilesystemException
         * @throws ORMException
         */
        public function uploadAvatar(Request $request, User $user): User{
            $file = $this->fileService->validateFile($request, FileService::AVATAR_INPUT_NAME);
            
            $this->fileService->deleteFile($user->getAvatar());
            
            $fileName = $this->fileService->uploadFile($file, FileService::AVATAR_INPUT_NAME);
            
            $user->setAvatar($fileName);
            $this->repository->save($user);
            return $user;
        }
    }