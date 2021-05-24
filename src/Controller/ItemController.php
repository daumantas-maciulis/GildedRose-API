<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Model\ItemModel;
use App\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("api/v1/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("", methods="POST")
     */
    public function addNewItemAction(Request $request, ItemModel $itemModel, ValidatorInterface $validator, Serializer $serializer): JsonResponse
    {
        $deserializedData = $serializer->deserialize($request->getContent(), Item::class);
        $form = $this->createForm(ItemType::class, $deserializedData);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $responseFromModel = $itemModel->saveNewItem($form->getData());
            if (!$responseFromModel) {
                return $this->json("You have selected non existent category", Response::HTTP_BAD_REQUEST);
            }

            return $this->json($responseFromModel, Response::HTTP_CREATED, [], [
                ObjectNormalizer::IGNORED_ATTRIBUTES => [
                    'category'
                ]
            ]);
        }
        return $this->json($validator->validate($form), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", methods="PATCH")
     */
    public function updateItemAction(Item $item, Request $request, ItemModel $itemModel, ValidatorInterface $validator, Serializer $serializer): JsonResponse
    {
        $deserializedData = $serializer->deserialize($request->getContent(), Item::class);

        $form = $this->createForm(ItemType::class, $deserializedData);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $responseFromModel = $itemModel->updateItem($item, $form->getData());
            if (!$responseFromModel) {
                return $this->json("You have selected non existent category", Response::HTTP_BAD_REQUEST);
            }
            return $this->json($responseFromModel, Response::HTTP_OK);
        }

        return $this->json($validator->validate($form), Response::HTTP_BAD_REQUEST);
    }

}