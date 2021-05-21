<?php


namespace App\Controller;


use App\Entity\Item;
use App\Form\ItemType;
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
 * @Route("api/v1/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("", methods="POST")
     */
    public function addNewItemAction(Request $request, ItemModel $itemModel, ValidatorInterface $validator): JsonResponse
    {
        $serialzer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $deserializedData = $serialzer->deserialize($request->getContent(), Item::class, 'json');

        $form = $this->createForm(ItemType::class, $deserializedData);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $responseFromModel = $itemModel->saveNewItem($form->getData());
            if (!$responseFromModel) {
                return $this->json("You have selected non existent category", Response::HTTP_BAD_REQUEST);
            }

            return $this->json($responseFromModel, Response::HTTP_OK, [], [
                ObjectNormalizer::IGNORED_ATTRIBUTES => [
                    'category'
                ]
            ]);
        }

        $errors = $validator->validate($form);

        return $this->json($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", methods="PATCH")
     */
    public function updateItemAction(Item $item, Request $request, ItemModel $itemModel, ValidatorInterface $validator): JsonResponse
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $deserializedData = $serializer->deserialize($request->getContent(), Item::class, 'json');

        $form = $this->createForm(ItemType::class, $deserializedData);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $responseFromModel = $itemModel->updateItem($item, $form->getData());
            if (!$responseFromModel) {
                return $this->json("You have selected non existent category", Response::HTTP_BAD_REQUEST);
            }
            return $this->json($responseFromModel, Response::HTTP_OK);
        }
        $errors = $validator->validate($form);

        return $this->json($errors, Response::HTTP_BAD_REQUEST);
    }

}