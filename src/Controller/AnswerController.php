<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answer/create/{id}", name="app_answer_create")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function create(Request $request, EntityManagerInterface $em, Question $question): Response
    {
        /**
         * @var UserInterface $user
         */
        $user = $this->getUser();

        $answer = new Answer();
        $answerForm = $this->createForm(AnswerType::class, $answer)
            ->handleRequest($request);

        $answer->setContent($answerForm->get('content')->getData())
            ->setQuestion($question)
            ->setUser($user)
            ->setIsValid(false);
        $em->persist($answer);
        $em->flush();

        return $this->redirectToRoute('app_question_show', [
            'id' => $question->getId(),
            'slug' => $question->getSlug()
        ]);
    }
}
