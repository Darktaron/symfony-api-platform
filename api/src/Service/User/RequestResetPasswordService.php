<?php
    
    namespace App\Service\User;
    
    use App\Messenger\Message\RequestResetPasswordMessage;
    use App\Messenger\RoutingKey;
    use App\Repository\UserRepository;
    use App\Service\Request\RequestService;
    use Doctrine\ORM\OptimisticLockException;
    use Doctrine\ORM\ORMException;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
    use Symfony\Component\Messenger\MessageBusInterface;

    class RequestResetPasswordService{
        private UserRepository      $repository;
        private MessageBusInterface $bus;
    
        public function __construct(UserRepository $repository, MessageBusInterface $bus){
            $this->repository = $repository;
            $this->bus = $bus;
        }
    
        /**
         * @throws ORMException
         * @throws OptimisticLockException
         */
        public function send(string $email): void{
            $user = $this->repository->findOneByEmailOrFail($email);
            $user->setResetPasswordToken(\sha1(\uniqid()));
            
            $this->repository->save($user);
            
            $this->bus->dispatch(
                new RequestResetPasswordMessage($user->getId(), $user->getEmail(), $user->getResetPasswordToken()),
                [new AmqpStamp(RoutingKey::USER_QUEUE)]
            );
        }
    }