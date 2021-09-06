<?php

namespace App\Service;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionService
{
    private EntityManagerInterface $em;
    private SessionService $sessionService;

    public function __construct(
        EntityManagerInterface $em,
        SessionService $sessionService
    ) {
        $this->em = $em;
        $this->sessionService = $sessionService;
    }

    public function createQuestion(Question $question): void
    {
        $this->em->persist($question);
        $this->em->flush();
        $this->sessionService->setFlashMessage('success', 'Question ajoutée avec succès');
    }

    public function updateQuestion(): void
    {
        $this->em->flush();
        $this->sessionService->setFlashMessage('success', 'Question éditée avec succès');
    }

    public function deleteQuestion(Question $question): void
    {
        $this->em->remove($question);
        $this->em->flush();
        $this->sessionService->setFlashMessage('success', 'Question supprimée avec succès');
    }
}
