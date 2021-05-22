<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class ItemFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->loadItems($manager);
    }

    private function loadItems($manager)
    {
        foreach ($this->getItems() as [$categoryName, $itemName, $value, $quality, $sellIn])
        {
            /**@var EntityManagerInterface$manager */

            $item = new Item();
            $item->setCategoryName($categoryName);
            $item->setName($itemName);
            $item->setValue($value);
            $item->setQuality($quality);
            $item->setSellIn($sellIn);
            $category = $manager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
            $item->setCategory($category);

            $manager->persist($item);
        }
        $manager->flush();
    }

    private function getItems()
    {
        return [
            ["Aged brie", "white one_item", 10, 50, 0],
            ["Aged brie", "Combazola_item", 30, 45, 3],
            ["Aged brie", "Sort of cheder but not_item", 40, 20, 3],

            ["Backstage passes", "Led Zeppelin_item", 22, 50, 1],
            ["Backstage passes", "Matador_item", 22, 15, 20],
            ["Backstage passes", "Slipknot_item", 22, 15, 15],

            ["Sulfuras", "Hand of Ragnaros_item", 25, 1, 1],
            ["Sulfuras", "Hand of Veryga_item", 30, 15, 3],
            ["Sulfuras", "Hand of Dzeus_item", 44, 25, 40],

            ["Conjured", "Mana Cake_item", 1, -2, 22],
            ["Conjured", "Shovel_item", 22, 23, 33],
            ["Conjured", "Chain_item", 32, 44, 44],

            ["Milk", "Miau_item", 22, 1, 1],
            ["Milk", "Moo_item", 22, 36, 2],
            ["Milk", "Cashew_item", 22, 34, 3],
        ];
    }
}

