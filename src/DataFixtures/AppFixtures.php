<?php

namespace App\DataFixtures;

use App\Entity\GroupEntity;
use App\Entity\MeasurementEntity;
use App\Entity\SourceEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $groupA = new GroupEntity();
        $groupA->setId(1);
        $groupA->setName('Group A');
        $manager->persist($groupA);

        $sourceA = new SourceEntity();
        $sourceA->setId(1);
        $sourceA->setName('Source A');
        $sourceA->setUnit('Seconds');
        $sourceA->setGroup($groupA);
        $manager->persist($sourceA);

        for ($i = 1; $i < 20; $i++) {
            $measurement = new MeasurementEntity();
            $measurement->setId($i);
            $measurement->setValue(mt_rand(0, 100));
            $measurement->setSource($sourceA);
            $manager->persist($measurement);
        }

        $manager->flush();
    }
}
