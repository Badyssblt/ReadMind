<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPostProcessor implements ProcessorInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher,
                                #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
                                private ProcessorInterface $persistProcessor,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if($data instanceof User){
            $data->setPassword($this->hasher->hashPassword($data, $data->getPassword()));

            $token = bin2hex(random_bytes(32));

            $data->setToken($token);
        }


        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
