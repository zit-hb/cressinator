<?php

namespace App\DataFixtures;

use App\Entity\GroupEntity;
use App\Entity\MeasurementEntity;
use App\Entity\MeasurementSourceEntity;
use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($k = 1; $k < 25; $k++) {
            $group = new GroupEntity();
            $group->setName('Group ' . $k);
            $manager->persist($group);

            for ($j = 1; $j < 5; $j++) {
                $measurementSource = new MeasurementSourceEntity();
                $measurementSource->setName('Source ' . $j);
                $measurementSource->setUnit('Seconds');
                $measurementSource->setGroup($group);
                $manager->persist($measurementSource);

                for ($i = 1; $i < 20; $i++) {
                    $measurement = new MeasurementEntity();
                    $measurement->setValue(mt_rand(0, 100));
                    $measurement->setSource($measurementSource);
                    $measurement->setCreatedAt(new \DateTime(mt_rand(0, 100) . ' minutes'));
                    $manager->persist($measurement);
                }
            }
        }

        $manager->flush();
    }
}
