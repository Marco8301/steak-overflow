<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Service\AnswerService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    private AnswerService $service;

    public function __construct(AnswerService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/answer/create/{id}", name="app_answer_create", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function create(Request $request, EntityManagerInterface $em, Question $question): Response
    {
        $answer = new Answer();
        $answerForm = $this->createForm(AnswerType::class, $answer)
            ->handleRequest($request);
        $this->service->createAnswer($answer, $answerForm->get('content')->getData(), $question);

        return $this->redirectToRoute('app_question_show', [
            'id' => $question->getId(),
            'slug' => $question->getSlug()
        ]);
    }

    /**
     * @Route("/answer/delete/{id<[0-9]+>}", name="app_answer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Answer $answer): Response
    {
        if ($this->isCsrfTokenValid('answer.delete' . $answer->getId(), (string)$request->request->get('csrf_token'))) {
            $this->service->deleteAnswer($answer);
        }

        return $this->redirectToRoute('app_question_show', [
            'id' => $answer->getQuestion()->getId(),
            'slug' => $answer->getQuestion()->getSlug()
        ]);
    }
}
