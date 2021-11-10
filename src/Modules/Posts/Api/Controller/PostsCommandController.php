<?php

namespace App\Modules\Posts\Api\Controller;

use App\Infrastructure\Security\Permission;
use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\DeletePostCommand;
use App\Modules\Posts\Api\Command\UpdatePostCommand;
use App\Modules\Posts\Api\PostsApiInterface;
use App\Modules\Posts\Domain\Security\PostsVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

#[Route('/api/admin/posts')]
class PostsCommandController extends AbstractController
{
    /**
     * @param PostsApiInterface $postsApi
     */
    public function __construct(
        private PostsApiInterface $postsApi
    )
    {
    }

    /**
     * @param CreatePostCommand $command
     * @return Response
     */
    #[Route('/', methods: ['POST'])]
    public function createPost(CreatePostCommand $command): Response
    {
        return $this->json(
            $this->postsApi->createPost($command)
        );
    }

    /**
     * @param UpdatePostCommand $command
     * @param string $id
     * @return Response
     */
    #[Route('/{id}', methods: ['PUT'])]
    public function updatePost(UpdatePostCommand $command, string $id): Response
    {
        $command->setId(new Ulid($id));
        if(!$this->isGranted(Permission::EDIT, $command)) {
            throw new UnauthorizedHttpException(Permission::EDIT);
        }
        $this->postsApi->updatePost($command);
        return new Response();
    }

    /**
     * @param string $id
     * @return Response
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function deletePost(string $id): Response
    {
        $command = new DeletePostCommand(new Ulid($id));
        if(!$this->isGranted(Permission::DELETE, $command)) {
            throw new UnauthorizedHttpException(Permission::DELETE);
        }
        $this->postsApi->deletePost($command);
        return new Response();
    }

}