<?php

namespace App\Tests\Modules\Security\Unit;

use App\Modules\Security\Api\Event\Inbound\CommentsCountUpdatedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostCreatedSecurityIEvent;
use App\Modules\Security\Api\Query\FindPostsByUserIdQuery;
use App\Tests\Modules\Security\Unit\Repository\InMemoryUser;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use Symfony\Component\Uid\Ulid;

class CommentsEventsSecurityHandlingSpec extends SecuritySpec
{
    use ApplicationEventContractLoader;


    /**
     * @test
     */
    public function shouldUpdateCommentsCountUponCommentsCountUpdatedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedSecurityIEvent($this->getInboundEvent("Security/PostCreatedSecurityIEvent"));

        //and: the Event was already published
        $this->securityApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new CommentsCountUpdatedSecurityIEvent($this->getInboundEvent("Security/CommentsCountUpdatedSecurityIEvent"));

        //when: the Event was published
        $this->securityApi->onCommentsCountUpdated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->securityApi->findPostsByUserId(new FindPostsByUserIdQuery(new Ulid(InMemoryUser::ID), 1));
        self::assertNotNull($headers);
        self::assertCount(1, $headers->getData());
        self::assertTrue(isset($headers->getData()[0]));
        self::assertEquals($event->getPostId(), $headers->getData()[0]->getId());
        self::assertEquals($event->getCommentsCount(), $headers->getData()[0]->getCommentsCount());

    }

}