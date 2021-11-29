<?php

namespace App\Tests\Modules\Security\Integration\Http;

use App\Tests\TestUtils\SerializationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait SecurityHttpTrait
{

    use SerializationTrait;

    /**
     * @return KernelBrowser
     */
    public abstract function getClient(): KernelBrowser;
}