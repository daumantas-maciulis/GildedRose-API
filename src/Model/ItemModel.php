<?php


namespace App\Model;


use App\Entity\Category;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;

class ItemModel
{
    private $entityManager;
    private $itemRepository;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->itemRepository = $entityManager->getRepository(Item::class);
        $this->categoryRepository = $entityManager->getRepository(Category::class);

    }

    private function saveData(Item $item): Item
    {
        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $item;
    }

    public function saveNewItem(Item $item): ?Item
    {
        $category = $this->categoryRepository->findOneBy(['name' => $item->getCategoryName()]);
        if(!$category)
        {
            return null;
        }

        /** @var Category $category */
        $item->setCategory($category);

        return $this->saveData($item);
    }

    public function updateItem(Item $item, Item $itemFromForm): ?Item
    {
        $category = $this->categoryRepository->findOneBy(['name' => $itemFromForm->getCategoryName()]);
        if(!$category)
        {
            return null;
        }

        /** @var Category $category */
        $item->setCategory($category);
        $item->setCategoryName($itemFromForm->getCategoryName());
        $item->setName($itemFromForm->getName());
        $item->setQuality($itemFromForm->getQuality());
        $item->setValue($itemFromForm->getValue());

        return $this->saveData($item);
    }
}