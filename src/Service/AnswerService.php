<?php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AnswerService
{
    private EntityManagerInterface $em;
    private SessionService $sessionService;
    private Security $security;

    public function __construct(
        EntityManagerInterface $em,
        SessionService $sessionService,
        Security $security
    ) {
        $this->em = $em;
        $this->sessionService = $sessionService;
        $this->security = $security;
    }

    public function createAnswer(Answer $answer, ?string $content, Question $question): void
    {
        $answer->setUser($this->security->getUser())
            ->setContent($content)
            ->setQuestion($question);
        $this->em->persist($answer);
        $this->em->flush();
        $this->sessionService->setFlashMessage('success', 'Réponse ajoutée avec succès');
    }

    public function deleteAnswer(Answer $answer): void
    {
        $this->em->remove($answer);
        $this->em->flush();
        $this->sessionService->setFlashMessage('success', 'Réponse supprimée avec succès');
    }

    public function validateAnswer(Answer $answer, Question $question): void
    {
        $question->setIsClosed(true);
        $answer->setIsValid(true);
        $this->em->flush();
    }
}
