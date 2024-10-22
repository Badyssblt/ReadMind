<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\BookRepository;
use Symfony\Bundle\SecurityBundle\Security;

class BookGetProvider implements ProviderInterface
{

    private BookRepository $bookRepository;
    private Security $security;

    public function __construct(BookRepository $bookRepository, Security $security)
    {
        $this->bookRepository = $bookRepository;
        $this->security = $security;
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \RuntimeException('User not found');
        }



        return $this->bookRepository->findBy(['owner' => $user]);
    }
}
