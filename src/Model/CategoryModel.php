<?php


namespace App\Model;


use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryModel
{
    private $entityManager;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $entityManager->getRepository(Category::class);
    }

    private function saveData(Category $category):Category
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    public function saveCategory(Category $category): Category
    {
        return $this->saveData($category);
    }
}