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
            'questions' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/question/{id<[0-9]+>}/{slug}", name="app_question_show", methods={"GET"})
     */
    public function show(Question $question): Response
    {
        if (null == $question) {
            throw $this->createNotFoundException();
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
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
            'form' => $form,
        ]);
    }

    /**
     * @Route("/question/edit/{id<[0-9]+>}", name="app_question_edit", methods={"GET", "PUT"})
     */
    public function edit(Request $request, EntityManagerInterface $em, Question $question): Response
    {
        if (null == $question) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(QuestionType::class, $question, [
            'method' => 'PUT'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Question éditée avec succès');

            return $this->redirectToRoute('app_question_index');
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/question/delete/{id<[0-9]+>}", name="app_question_delete", methods={"GET", "DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em, Question $question): Response
    {
        if (null == $question) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('question.delete' . $question->getId(), (string) $request->request->get('csrf_token'))) {
            $em->remove($question);
            $em->flush();
            $this->addFlash('success', 'Question supprimée avec succès');
        }

        return $this->redirectToRoute('app_question_index');
    }
}
