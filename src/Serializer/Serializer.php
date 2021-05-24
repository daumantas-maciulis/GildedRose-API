<?php
declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\User;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SerializationService;

class Serializer
{
    public function deserialize($contentData, $class): User|Item|Category
    {
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $serializer = new SerializationService([$normalizer], [$encoder]);

        return $serializer->deserialize($contentData, $class, 'json');
    }
}

