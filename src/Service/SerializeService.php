<?php

namespace App\Service;

use JMS\Serializer\Serializer;

class SerializeService
{
    /** @var Serializer */
    private $serializer;

    /**
     * SerializeService constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $object
     * @param bool $raw
     * @return mixed
     */
    public function normalize($object, bool $raw = false)
    {
        if ($object === null) {
            return null;
        }

        if ($raw) {
            return $this->serializer->serialize($object, 'json');
        }

        return json_decode($this->serializer->serialize($object, 'json'));
    }
}
