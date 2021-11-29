<?php

namespace App\Modules\Posts\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;

class UserRenamedPostsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "USER_RENAMED";

    private string $oldLogin;

    private string $newLogin;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct( $data);
        $this->oldLogin = $this->string('oldLogin');
        $this->newLogin = $this->string('newLogin');
    }

    /**
     * @return string
     */
    public function getOldLogin(): string
    {
        return $this->oldLogin;
    }

    /**
     * @return string
     */
    public function getNewLogin(): string
    {
        return $this->newLogin;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::EVENT_NAME;
    }
}