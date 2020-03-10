<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserPersonal() as [$user_id, $user_name, $user_custom, $email, $balance, $register_date, $enabled]) {
            $user = new User();
            $user->setUserId($user_id);
            $user->setUserName($user_name);
            $user->setUserCustom($user_custom);
            $user->setEmail($email);
            $user->setBalance($balance);
            $user->setRegisterDate($register_date);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserPersonal(): array
    {
        return [
            ['1', 'Jane Doe', 'jane_admin', 'jane_admin@symfony.com', 0, '2020-03-10T08:14:03Z', true],
            ['2', 'Tom Doe', 'tom_admin', 'tom_admin@symfony.com', 100, '2020-01-10T10:23:51Z', true],
            ['3', 'John Doe', 'john_user', 'john_user@symfony.com', 2000, '2018-03-09T09:19:23Z', true]
        ];
    }
}
