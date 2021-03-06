<?php
declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Model\UserModel;
use App\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/create-account", methods="POST")
     */
    public function registerNewUserAction(Request $request, UserModel $userModel, ValidatorInterface $validator, Serializer $serializer): JsonResponse
    {
        $deserializedData = $serializer->deserialize($request->getContent(), User::class);

        $form = $this->createForm(RegistrationType::class, $deserializedData);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {
            $returnedUser = $userModel->saveNewUser($form->getData());

            if (!$returnedUser) {
                $responseMessage = sprintf("Account with email '%s' is taken, choose different", $form->getData()->getEmail());
                return $this->json($responseMessage, Response::HTTP_BAD_REQUEST);
            }

            return $this->json($returnedUser, Response::HTTP_CREATED, [], [
                ObjectNormalizer::IGNORED_ATTRIBUTES => ['password', 'username', 'salt']
            ]);
        }

        $errors = $validator->validate($form);

        return $this->json($errors, Response::HTTP_BAD_REQUEST);
    }
}

