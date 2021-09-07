<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class AnswerFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Faker::create('fr_FR');
        $this->generateAnswers(20);
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,
            UserFixtures::class,
        ];
    }

    private function generateAnswers(int $number): void
    {
        for ($i = 0; $i <= $number; $i++) {
            /**
             * @var Question $question
             * @var User $user
             */
            $question = $this->getReference("question" . mt_rand(0, 6));
            $user = $this->getReference("user" . mt_rand(0, 5));
            $answer = (new Answer())
                ->setContent($this->faker->text(60))
                ->setUser($user)
                ->setQuestion($question);
            $this->manager->persist($answer);
        }
    }
}
