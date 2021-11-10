<?php

namespace App\Modules\Security\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;

class UserRenamedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "USER_RENAMED";

    /**
     * @param string $oldLogin
     * @param string $newLogin
     */
    public function __construct(
        string $oldLogin,
        string $newLogin
    )
    {
        parent::__construct(self::EVENT_NAME,
            [
                'oldLogin' => $oldLogin,
                'newLogin' => $newLogin
            ]
        );
    }
}