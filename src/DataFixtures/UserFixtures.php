<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    private function loadUsers($manager)
    {
        foreach ($this->getUser() as [$email, $password, $role, $firstName, $lastName, $phoneNumber, $position])
        {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setRoles($role);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setPhoneNumber($phoneNumber);
            $user->setPosition($position);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUser(): array
    {
        return [
            ["admin@admin.com", '$argon2id$v=19$m=65536,t=4,p=1$CMcThvQf4bVUGnGnY1ISOw$c7Gmme6eZ3kEN4miUM23+PHpPrz6f4wkin7IG0FCYMY', ["ROLE_ADMIN"], "name", "lastname", "+37061111111", "ceo"],
            ["labas@labas.com", '$argon2id$v=19$m=65536,t=4,p=1$CMcThvQf4bVUGnGnY1ISOw$c7Gmme6eZ3kEN4miUM23+PHpPrz6f4wkin7IG0FCYMY', ["ROLE_USER"], "name", "lastname", "+37061111111", "ceo"],

        ];
    }
}