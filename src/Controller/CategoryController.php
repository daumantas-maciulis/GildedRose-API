<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Model\CategoryModel;
use App\Model\ItemModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api/v1/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", methods="POST")
     */
    public function createCategoryAction(Request $request, CategoryModel $categoryModel, ValidatorInterface $validator): JsonResponse
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $deserializedData = $serializer->deserialize($request->getContent(), Category::class, 'json');
        $form = $this->createForm(CategoryType::class, $deserializedData);

        $form->submit(json_decode($request->getContent(), true));

        if($form->isSubmitted() && $form->isValid())
        {
            $savedCategory = $categoryModel->saveCategory($form->getData());

            return $this->json($savedCategory, Response::HTTP_OK);
        }

        $errors = $validator->validate($form);

        return $this->json($errors, Response::HTTP_BAD_REQUEST);
    }


    /**
     * @Route("", methods="GET")
     */
    public function getItemsFromCategoryAction(Request $request, CategoryModel $categoryModel): JsonResponse
    {
        $category = $categoryModel->fetchCategory($request->get('name'));
        if(!$category)
        {
            return $this->json("Please select existent category", Response::HTTP_BAD_REQUEST);
        }

        return $this->json($category, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['categoryName'],
            ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
            return $object->getName();
            }
        ]);
    }

    /**
     * @Route("", methods="DELETE")
     */
    public function deleteItemsFromCategoryAction(Request $request, CategoryModel $categoryModel, ItemModel $itemModel): JsonResponse
    {
        $categoryName = $request->get('name');
        $items = $categoryModel->fetchItemsFromCategory($categoryName);
        if(!$items)
        {
            return $this->json("Please select existent category", Response::HTTP_BAD_REQUEST);
        }
        foreach($items as $item)
        {
            $itemModel->deleteItem($item);
        }
        $responseMessage = sprintf("Items from category '%s' was successfully deleted", $categoryName);
        return $this->json($responseMessage, Response::HTTP_OK);
    }
}