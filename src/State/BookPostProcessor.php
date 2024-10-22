<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Book;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\User\UserInterface;

class BookPostProcessor implements ProcessorInterface
{
    public function __construct(private Security $security,
                                #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
                                private ProcessorInterface $persistProcessor,)
    {

    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();

        if(!$user) {
            throw new \RuntimeException('User not authenticated');
        }

        if($data instanceof Book){
            $data->setOwner($user);

            $data->setName($this->slugToTitle($data->getSlug()));
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);

    }


    private function slugToTitle(string $slug): string
    {
        $title = str_replace('-', ' ', $slug);

        return ucwords($title);
    }
}
