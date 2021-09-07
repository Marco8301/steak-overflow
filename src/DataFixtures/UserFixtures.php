<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class UserFixtures extends Fixture
{
    private Generator $faker;
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Faker::create('fr_FR');
        $this->generateUsers(5);
        $this->manager->flush();
    }

    private function generateUsers(int $number): void
    {
        for ($i = 0; $i <= $number; $i++) {
            $user = (new User())
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setPassword((string)mt_rand(0, 9999));
            $this->addReference("user{$i}", $user);
            $this->manager->persist($user);
        }
    }
}
