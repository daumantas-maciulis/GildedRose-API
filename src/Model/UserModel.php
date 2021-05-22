<?php


namespace App\Model;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserModel
{
    private $entityManager;
    private $userRepository;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->passwordEncoder = $passwordEncoder;
    }

    private function saveData(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function saveNewUser(User $userFromForm): ?User
    {
        $emailAlreadyTaken = $this->userRepository->findOneBy(['email' => $userFromForm->getEmail()]);

        if ($emailAlreadyTaken) {
            return null;
        }

        $user = new User();

        $user->setEmail($userFromForm->getEmail());
        $user->setRoles(["ROLE_USER"]);
        $user->setFirstName($userFromForm->getFirstName());
        $user->setLastName($userFromForm->getLastName());
        $user->setPhoneNumber($userFromForm->getPhoneNumber());
        $user->setPhoneNumber($user->getPhoneNumber());
        $user->setPosition($userFromForm->getPosition());

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $userFromForm->getPassword());
        $user->setPassword($encodedPassword);

        $this->saveData($user);

        return $user;
    }
}