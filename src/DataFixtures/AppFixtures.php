<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $service = new Service();
            $service->setName('Service ' . $i);
            $service->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent hendrerit condimentum nisl a efficitur. Maecenas tincidunt mollis enim in maximus. Nullam mollis urna volutpat vehicula commodo. Aenean vehicula risus nisl, ut bibendum eros efficitur id. Phasellus posuere in magna et laoreet. Quisque at est tellus.');
            $service->setDuration(($i + 1) * 10);
            $service->setPrice($i * 10);
            $service->setCity('Lille');
            $service->setCityCode(59000);
            $service->setAdress($i . ' Rue du moulin');
            $manager->persist($service);
        }

        $manager->flush();
    }
}
