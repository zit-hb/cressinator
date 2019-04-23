<?php

namespace App\Twig;

use App\Service\SerializeService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JsonifyExtension extends AbstractExtension
{
    /** @var SerializeService */
    private $serializer;

    /**
     * JsonifyExtension constructor.
     * @param SerializeService $serializer
     */
    public function __construct(SerializeService $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('jsonify', [$this, 'jsonify']),
        ];
    }

    /**
     * @param mixed $entity
     * @return string
     */
    public function jsonify($entity): string
    {
        return $this->serializer->normalize($entity, true);
    }
}
