<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Faker::create('fr_FR');
        $this->generateQuestions(7);
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    private function generateQuestions(int $number): void
    {
        for ($i = 0; $i <= $number; $i++) {
            /**
             * @var User $user
             */
            $user = $this->getReference("user" . mt_rand(0, 5));
            $question = (new Question())
                ->setTitle($this->faker->text(60))
                ->setContent($this->faker->text())
                ->setUser($user);
            $this->addReference("question{$i}", $question);
            $this->manager->persist($question);
        }
    }
}
