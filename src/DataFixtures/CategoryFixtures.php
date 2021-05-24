<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
    }

    private function loadCategories($manager)
    {
        foreach ($this->getCategories() as [$name]) {
            $category = new Category();
            $category->setName((string)$name);
            $manager->persist($category);
        }

        $manager->flush();
    }

    private function getCategories(): array
    {
        return [
            ["Aged Brie"],
            ["Backstage passes"],
            ["Sulfuras"],
            ["Conjured"],
            ["Milk"],
            ["Laptops"]
        ];
    }
}

