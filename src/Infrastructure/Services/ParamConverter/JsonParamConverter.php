<?php

namespace App\Infrastructure\Services\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Param Converter for de/serializing the REST Requests and Responses
 */
class JsonParamConverter implements ParamConverterInterface
{
    /**
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private ValidatorInterface  $validator,
        private SerializerInterface $serializer
    )
    {

    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $request->attributes->set($configuration->getName(),
            $this->validate(
                $this->deserialize($request, $configuration->getClass())
            )
        );
    }

    /**
     * @param ParamConverter $configuration
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() != null;
    }

    /**
     * @param Request $request
     * @param string $class
     * @return mixed
     */
    public function deserialize(Request $request, string $class): mixed
    {
        try {
            $object = $this->serializer->deserialize(
                $request->getContent(),
                $class,
                'json'
            );
        } catch (\RuntimeException $e) {
            throw new BadRequestException(sprintf('Could not deserialize request content to object of type "%s": %s',
                $class, $e->getMessage()));
        }
        return $object;
    }

    /**
     * @param mixed $object
     * @return mixed
     */
    public function validate(mixed $object): mixed
    {
        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            throw new BadRequestException(sprintf('Invalid Request Body: %s',
                (string)$errors));
        }
        return $object;
    }
}