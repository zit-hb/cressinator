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
     * @param $object
     * @return mixed
     */
    public function normalize($object)
    {
        return json_decode($this->serializer->serialize($object, 'json'));
    }
}
