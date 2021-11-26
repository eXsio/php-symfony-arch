<?php

namespace App\Tests\TestUtils;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Serializer\SerializerInterface;

trait SerializationTrait
{
    private ?SerializerInterface $serializer = null;

    protected function json(mixed $obj): string
    {
        return $this->getSerializer()->serialize($obj, 'json');
    }

    protected function object(string $json, string $class): mixed
    {
        return $this->getSerializer()->deserialize($json, $class, 'json');
    }

    protected function convert(array $data, string $class): mixed
    {
        return $this->getSerializer()->deserialize(json_encode($data), $class, 'json');
    }

    protected function responseObject(KernelBrowser $client, string $class): mixed
    {
        $content = $client->getResponse()->getContent();
        return $this->object($content, $class);
    }

    protected function responseObjects(KernelBrowser $client, string $class): array
    {
        $content = $client->getResponse()->getContent();
        $array = json_decode($content, true);
        $result = [];
        foreach ($array as $item) {
            array_push($result, $this->convert($item, $class));
        }
        return $result;

    }

    private function getSerializer(): SerializerInterface
    {
        return self::getContainer()->get('serializer');
    }
}