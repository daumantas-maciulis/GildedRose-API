<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryModel
{
    private EntityManagerInterface $entityManager;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $entityManager->getRepository(Category::class);
    }

    private function saveData(Category $category): Category
    {
        //todo make try catch exception handling
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    private function deleteData(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    public function saveCategory(Category $category): ?Category
    {
        $categoryInDb = $this->categoryRepository->findOneBy(['name'=>$category->getName()]);
        if($categoryInDb)
        {
            return null;
        }

        return $this->saveData($category);
    }

    public function fetchCategory(string $categoryName): ?Category
    {
        $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$category) {
            return null;
        }
        /** @var Category $category */
        return $category;
    }

    public function fetchItemsFromCategory(string $categoryName)
    {
        $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);
        if (!$category) {
            return null;
        }
        /** @var Category $category */
        return $category->getItems();
    }

    public function deleteCategory(string $categoryName): bool
    {
        $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);
        if(!$category)
        {
            return false;
        }
        /** @var Category $category */
        $this->deleteData($category);

        return true;
    }

    public function updateCategory(Category $categoryFromForm, Category $categoryFromDb): Category
    {
        $categoryFromDb->setName($categoryFromForm->getName());

        return $this->saveData($categoryFromDb);
    }
}

