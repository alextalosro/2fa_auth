<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
	    UserFactory::createOne(['email' => 'alex.talos@example.com', 'firstName' => 'Alex']);
	    UserFactory::createMany(10);

        $manager->flush();
    }
}
