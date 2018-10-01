<?php

namespace App\DataFixtures;
use App\Entity\Field;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class fieldFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $descriptions = array('Math', 'Art', 'History', 'Language');
        for($i = 0; $i < count($descriptions); $i++)
        {
            $field = new Field();
            $field->setName($descriptions[$i]);
            $manager->persist($field);
        }
        $manager->flush();
    }
}