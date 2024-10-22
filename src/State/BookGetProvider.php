<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class BookGetProvider implements ProviderInterface
{

    private BookRepository $bookRepository;
    private Security $security;

    private UserRepository $userRepository;

    public function __construct(BookRepository $bookRepository, Security $security, UserRepository $userRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $token = $context['filters']['token'] ?? null;

        $user = null;

        if($token){
            $user = $this->userRepository->findOneBy(['token' => $token]);
        }else {
            $user = $this->security->getUser();
        }

        if (!$user) {
            throw new \RuntimeException('User not found');
        }



        return $this->bookRepository->findBy(['owner' => $user]);
    }
}
