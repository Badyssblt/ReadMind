<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class BookPatchProcessor implements ProcessorInterface
{
    public function __construct(private Security $security,
                                private RequestStack $requestStack,
                                private BookRepository $bookRepository,
                                private UserRepository $userRepository,
                                #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
                                private ProcessorInterface $persistProcessor,
                                )
    {

    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        // Récupérer le token dans la query
        $token = $context['request']->query->get('token');

        $user = $this->userRepository->findOneBy(['token' => $token]);

        $request = $this->requestStack->getCurrentRequest();


        if(!$user) {
            throw new \RuntimeException('User not authenticated');
        }

        $book = $this->bookRepository->findOneBy(['slug' => $uriVariables['slug'], 'owner' => $user]);

        if(!$book){
            if($data instanceof Book && $data->getSlug() === null) {
                $data->setSlug($uriVariables['slug']);
                $data->setName($this->slugToTitle($data->getSlug()));
                $data->setOwner($user);
                $data->setCurrentChapter($request->getPayload()->get('chapter'));
                $data->setImage($request->getPayload()->get('image'));
            }
        }else {
            if($data instanceof Book){
                $book->setCurrentChapter($request->getPayload()->get('chapter'));
                return $this->persistProcessor->process($book, $operation, $uriVariables, $context);
            }
        }





        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);

    }


    private function slugToTitle(string $slug): string
    {
        $title = str_replace('-', ' ', $slug);

        return ucwords($title);
    }
}
