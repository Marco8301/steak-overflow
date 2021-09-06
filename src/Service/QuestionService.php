<?php

namespace App\Service;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class QuestionService
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

    public function createQuestion(Question $question): void
    {
        $question->setUser($this->security->getUser());
        $this->em->persist($question);
        $this->em->flush();
        $this->sessionService->setFlashMessage('success', 'Question créée avec succès');
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
