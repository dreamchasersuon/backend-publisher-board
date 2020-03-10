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
        foreach ($this->getUserPersonal() as [$user_id, $user_name, $user_custom, $email]) {
            $user = new User();
            $user->setUserId($user_id);
            $user->setUserName($user_name);
            $user->setUserCustom($user_custom);
            $user->setEmail($email);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserPersonal(): array
    {
        return [
            ['1', 'Jane Doe', 'jane_admin', 'jane_admin@symfony.com'],
            ['2', 'Tom Doe', 'tom_admin', 'tom_admin@symfony.com'],
            ['3', 'John Doe', 'john_user', 'john_user@symfony.com']
        ];
    }
}
