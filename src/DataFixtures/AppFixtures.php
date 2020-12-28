<?php

namespace App\DataFixtures;

use App\Entity\GroupEntity;
use App\Entity\MeasurementEntity;
use App\Entity\SourceEntity;
use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $group = new GroupEntity();
        $group->setName('Group 1');
        $manager->persist($group);

        for ($j = 1; $j < 5; $j++) {
            $source = new SourceEntity();
            $source->setName('Source ' . $j);
            $source->setUnit('Seconds');
            $source->setGroup($group);
            $manager->persist($source);

            for ($i = 1; $i < 20; $i++) {
                $measurement = new MeasurementEntity();
                $measurement->setValue(mt_rand(0, 100));
                $measurement->setSource($source);
                $measurement->setCreatedAt(new \DateTime(mt_rand(0, 100) . ' minutes'));
                $manager->persist($measurement);
            }
        }

        $manager->flush();
    }
}
