<?php

namespace App\Infrastructure\Events;

use RuntimeException;
use Symfony\Component\Uid\Ulid;

/**
 * This is a base class for all Application Inbound Events.
 * An Inbound Event is an Event that has originated from some other, possibly remote Application/Module.
 *
 * In the Context of this Demo, each Inbound Event is paired with a corresponding Outbound Event.
 * The pairing is based on an Event Name (string).
 */
abstract class ApplicationInboundEvent
{

    private Ulid $_eventId;

    /**
     * @param array $data
     */
    public function __construct(
        private array  $data
    )
    {
        $this->_eventId = $this->data['_eventId'];
    }

    /**
     * @param string $fieldName
     * @param bool $nullable
     * @return Ulid|null
     */
    protected function ulid(string $fieldName, bool $nullable = false): ?Ulid
    {
        $value = $this->value($fieldName, $nullable);
        if ($value === null) {
            return null;
        }
        return $value instanceof Ulid ? $value : new Ulid($value);
    }

    /**
     * @param string $fieldName
     * @param bool $nullable
     * @return array|null
     */
    protected function array(string $fieldName, bool $nullable = false): ?array
    {
        $value = $this->value($fieldName, $nullable);
        if ($value === null) {
            return null;
        }
        return is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * @param string $fieldName
     * @param bool $nullable
     * @return int|null
     */
    protected function int(string $fieldName, bool $nullable = false): ?int
    {
        $value = $this->value($fieldName, $nullable);
        if ($value === null) {
            return null;
        }
        return is_int($value) ? $value : intval($value);
    }

    /**
     * @param string $fieldName
     * @param bool $nullable
     * @return \DateTime|null
     */
    protected function dateTime(string $fieldName, bool $nullable = false): ?\DateTime
    {
        $value = $this->value($fieldName, $nullable);
        if ($value === null) {
            return null;
        }
        if ($value instanceof \DateTime) {
            return $value;
        } else {
            $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s", $value);
            if (!$dateTime) {
                throw new RuntimeException(sprintf("Invalid Date Time: '%s'", $value));
            }
            return $dateTime;
        }
    }

    /**
     * @param string $fieldName
     * @param bool $nullable
     * @return string|null
     */
    protected function string(string $fieldName, bool $nullable = false): ?string
    {
        $value = $this->value($fieldName, $nullable);
        if ($value == null) {
            return null;
        }
        return is_string($value) ? $value : (string)$value;
    }

    /**
     * @param string $fieldName
     * @param bool $nullable
     * @return mixed
     */
    private function value(string $fieldName, bool $nullable = false): mixed
    {
        if (!isset($this->data[$fieldName])) {
            if ($nullable) {
                return null;
            }
            $name = call_user_func(get_called_class() . '::getName');
            throw new RuntimeException("Event $name requires a field '$fieldName' to be present");
        }
        return $this->data[$fieldName];
    }

    /**
     * @return Ulid
     */
    public function getEventId(): Ulid
    {
        return $this->_eventId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->data);
    }

    public static abstract function getName(): string;


}