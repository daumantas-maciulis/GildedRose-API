<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Category;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;

class ItemModel
{
    private EntityManagerInterface $entityManager;
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
        //todo try catch
            $this->entityManager->persist($item);
            $this->entityManager->flush();
            return $item;
    }

    private function deleteData(Item $item): void
    {
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }

    public function saveNewItem(Item $item): ?Item
    {
        $category = $this->categoryRepository->findOneBy(['name' => $item->getCategoryName()]);
        if (!$category) {
            return null;
        }

        /** @var Category $category */
        $item->setCategory($category);

        return $this->saveData($item);
    }

    public function updateItem(Item $item, Item $itemFromForm): ?Item
    {
        $category = $this->categoryRepository->findOneBy(['name' => $itemFromForm->getCategoryName()]);
        if (!$category) {
            return null;
        }

        /** @var Category $category */
        $item->setCategory($category);
        $item->setCategoryName($itemFromForm->getCategoryName());
        $item->setName($itemFromForm->getName());
        $item->setQuality($itemFromForm->getQuality());
        $item->setValue($itemFromForm->getValue());
        $item->setSellIn($itemFromForm->getSellIn());

        return $this->saveData($item);
    }

    public function deleteItem(Item $item): void
    {
        $this->deleteData($item);
    }

    public function deleteItemById(int $id): void
    {
        $item = $this->itemRepository->find($id);
        /** @var Item $item */
        $this->deleteData($item);
    }

    public function getAllItems(): array
    {
        return $this->itemRepository->findAll();
    }

    public function updateItemFromCommand(Item $item)
    {
        $this->saveData($item);
    }
}

