<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class userFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('Otto');
        $user1->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user1->setLevel('1');
        $manager->persist($user1);

        $user2= new User();
        $user2->setUsername('Anna');
        $user2->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user2->setLevel('0');
        $manager->persist($user2);

        $user3 = new User();
        $user3->setUsername('Adam');
        $user3->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user3->setLevel('0');
        $manager->persist($user3);

        $user4 = new User();
        $user4->setUsername('Gabriella');
        $user4->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user4->setLevel('1');
        $manager->persist($user4);

        $user5 = new User();
        $user5->setUsername('Karol');
        $user5->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $user5->setLevel('0');
        $manager->persist($user5);


        $manager->flush();
    }
}