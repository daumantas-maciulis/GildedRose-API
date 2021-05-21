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
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api/v1/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/item/", methods="DELETE")
     */
    public function deleteItemAction(Request $request, ItemModel $itemModel): JsonResponse
    {
        $itemModel->deleteItemById($request->get('id'));

        $responseMessage = sprintf("Your item No. %s was successfully deleted", $request->get('id'));

        return $this->json($responseMessage, Response::HTTP_OK);
    }

    /**
     * @Route("/category/", methods="DELETE")
     */
    public function deleteCategoryAction(Request $request, CategoryModel $categoryModel): JsonResponse
    {
        $responseFromModel = $categoryModel->deleteCategory($request->get('name'));

        if ($responseFromModel == false) {
            $responseMessage = sprintf("Category '%s' is non existent, please select available one", $request->get('name'));

            return $this->json($responseMessage, Response::HTTP_BAD_REQUEST);
        }

        return $this->json("Your category was deleted", Response::HTTP_OK);
    }

    /**
     * @Route("/category/", methods="PATCH")
     */
    public function updateCategoryAction(Request $request, CategoryModel $categoryModel, ValidatorInterface $validator)
    {
        $categoryFromDb = $categoryModel->fetchCategory($request->get('name'));
        if (!$categoryFromDb) {
            $responseMessage = sprintf("Category '%s' is non existent, please select available one", $request->get('name'));
            return $this->json($responseMessage, Response::HTTP_BAD_REQUEST);
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonDecode()]);
        $deserializedData = $serializer->deserialize($request->getContent(), Category::class, 'json');

        $form = $this->createForm(CategoryType::class, $deserializedData);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedCatetegory = $categoryModel->updateCategory($form->getData(), $categoryFromDb);

            return $this->json($updatedCatetegory, Response::HTTP_OK);
        }

        $errors = $validator->validate($form);

        return $this->json($errors, Response::HTTP_BAD_REQUEST);
    }
}