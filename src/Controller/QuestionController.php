<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_question_index", methods={"GET"})
     */
    public function index(QuestionRepository $repository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/question/create", name="app_question_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($question);
            $em->flush();
            $this->addFlash('success', 'Question ajoutée avec succès');

            return $this->redirectToRoute('app_question_index');
        }

        return $this->renderForm('question/create.html.twig', [
            'form' => $form
        ]);
    }
}
