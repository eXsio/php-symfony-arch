<?php

namespace App\Tests\Modules\Security\Unit;

use App\Modules\Security\Api\Event\Inbound\PostCreatedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostDeletedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostUpdatedSecurityIEvent;
use App\Modules\Security\Api\Query\FindUserPostHeadersQuery;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use Symfony\Component\Uid\Ulid;

class PostEventsSecurityHandlingSpec extends SecuritySpec
{
    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldCreatePostHeaderUponPostCreatedIEvent()
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedSecurityIEvent($this->getInboundEvent("Security/PostCreatedSecurityIEvent"));

        //when: the Event was published
        $this->securityApi->onPostCreated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->securityApi->findPostHeaders(new FindUserPostHeadersQuery());
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getId(), $headers[0]->getId());
        self::assertEquals($event->getTitle(), $headers[0]->getTitle());
        self::assertEquals($event->getSummary(), $headers[0]->getSummary());
        self::assertEquals($event->getTags(), $headers[0]->getTags());
        self::assertEquals($event->getCreatedAt(), $headers[0]->getCreatedAt());
        self::assertEquals(1, $headers[0]->getVersion());
    }

    /**
     * @test
     */
    public function shouldUpdatePostHeaderUponPostUpdatedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedSecurityIEvent($this->getInboundEvent("Security/PostCreatedSecurityIEvent"));

        //and: the Event was already published
        $this->securityApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostUpdatedSecurityIEvent($this->getInboundEvent("Security/PostUpdatedSecurityIEvent"));

        //when: the Event was published
        $this->securityApi->onPostUpdated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->securityApi->findPostHeaders(new FindUserPostHeadersQuery());
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getId(), $headers[0]->getId());
        self::assertEquals($event->getTitle(), $headers[0]->getTitle());
        self::assertEquals($event->getSummary(), $headers[0]->getSummary());
        self::assertEquals($event->getTags(), $headers[0]->getTags());
        self::assertEquals($event->getLastVersion(), $headers[0]->getVersion());
    }

    /**
     * @test
     */
    public function shouldDeletePostHeaderUponPostUpdatedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedSecurityIEvent($this->getInboundEvent("Security/PostCreatedSecurityIEvent"));

        //and: the Event was already published
        $this->securityApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostDeletedSecurityIEvent($this->getInboundEvent("Security/PostDeletedSecurityIEvent"));

        //when: the Event was published
        $this->securityApi->onPostDeleted($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->securityApi->findPostHeaders(new FindUserPostHeadersQuery());
        self::assertNotNull($headers);
        self::assertCount(0, $headers);
    }

}