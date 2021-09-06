<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Service\AnswerService;
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
    public function create(Request $request, Question $question): Response
    {
        $answer = new Answer();
        $answerForm = $this->createForm(AnswerType::class, $answer)
            ->handleRequest($request);

        if ($answerForm->isSubmitted() && $answerForm->isValid()) {
            $this->service->createAnswer($answer, $answerForm->get('content')->getData(), $question);
        }

        return $this->redirectToRoute('app_question_show', [
            'id' => $question->getId(),
            'slug' => $question->getSlug()
        ]);
    }

    /**
     * @Route("/answer/delete/{id<[0-9]+>}", name="app_answer_delete", methods={"DELETE"})
     * @IsGranted("MANAGE_ANSWER", subject="answer")
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

    /**
     * @Route("/answer/validate/{id<[0-9]+>}", name="app_answer_validate", methods={"PUT"})
     */
    public function validateAnswer(Request $request, Answer $answer): Response
    {
        $question = $answer->getQuestion();
        $this->denyAccessUnlessGranted('MANAGE_QUESTION', $question);
        if ($this->isCsrfTokenValid('answer.validate' . $answer->getId(), (string)$request->request->get('csrf_token'))) {
            $this->service->validateAnswer($answer, $question);
        }

        return $this->redirectToRoute('app_question_show', [
            'id' => $question->getId(),
            'slug' => $question->getSlug(),
        ]);
    }
}
