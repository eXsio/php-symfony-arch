<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Infrastructure\Security\LoggedInUser;
use App\Infrastructure\Security\LoggedInUserProviderInterface;
use App\Infrastructure\Utils\StringUtil;
use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\Response\CreatePostCommandResponse;
use App\Modules\Posts\Domain\Dto\CreateNewPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostCreatedOEvent;
use App\Modules\Posts\Domain\Repository\PostsCreationRepositoryInterface;
use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;

trait PostsCreator
{
    /**
     * @param ApplicationEventPublisherInterface $publisher
     * @param PostsCreationRepositoryInterface $creationRepository
     * @param LoggedInUserProviderInterface $loggedInUserProvider
     * @param PostTransactionFactoryInterface $transactionFactory
     * @param PostsValidator $validator
     */
    public function __construct(
        private ApplicationEventPublisherInterface $publisher,
        private PostsCreationRepositoryInterface   $creationRepository,
        private LoggedInUserProviderInterface      $loggedInUserProvider,
        private PostTransactionFactoryInterface    $transactionFactory,
        private PostsValidator                     $validator
    )
    {
    }

    /**
     * @param CreatePostCommand $command
     * @return CreatePostCommandResponse
     */
    function createPost(CreatePostCommand $command): CreatePostCommandResponse
    {
        $this->validator->preCreate($command);
        $newPost = $this->fromCreateCommand($command);
        $id = $this->transactionFactory
            ->createTransaction(function () use ($newPost) {
                return $this->creationRepository->createPost($newPost);
            })
            ->afterCommit(function ($id) use ($newPost) {
                $this->publisher->publish(new PostCreatedOEvent($id, $newPost));
            })
            ->execute();

        return new CreatePostCommandResponse($id);
    }

    /**
     * @param CreatePostCommand $command
     * @return CreateNewPostDto
     */
    private function fromCreateCommand(CreatePostCommand $command): CreateNewPostDto
    {
        return new CreateNewPostDto(
            $command->getTitle(),
            $command->getBody(),
            $this->getSummary($command->getBody()),
            $command->getTags(),
            $this->getUser()->getId(),
            $this->getUser()->getEmail(),
            new \DateTime()
        );
    }

    /**
     * @return LoggedInUser
     */
    private function getUser(): LoggedInUser
    {
        return $this->loggedInUserProvider->getUser();
    }

    /**
     * @param string $body
     * @return string
     */
    private function getSummary(string $body): string
    {
        return StringUtil::getSummary($body);
    }
}