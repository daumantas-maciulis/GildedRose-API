<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Model\CategoryModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("api/v1/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", methods="POST")
     */
    public function createCategoryAction(Request $request, CategoryModel $categoryModel): JsonResponse
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
    }
}