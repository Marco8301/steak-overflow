<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Service\QuestionService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private QuestionService $service;

    public function __construct(QuestionService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/", name="app_question_index", methods={"GET"})
     */
    public function index(Request $request, QuestionRepository $repository, PaginatorInterface $paginator): Response
    {
        $questions = $paginator->paginate(
            $repository->findBy([], [
                'isClosed' => 'ASC',
                'createdAt' => 'DESC',
            ]),
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
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
        $answer = new Answer();
        $answerForm = $this->createForm(AnswerType::class, $answer, [
            'action' => $this->generateUrl('app_answer_create', ['id' => $question->getId()])
        ]);

        return $this->renderForm('question/show.html.twig', [
            'question' => $question,
            'answerForm' => $answerForm
        ]);
    }

    /**
     * @Route("/question/create", name="app_question_create", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->createQuestion($question);

            return $this->redirectToRoute('app_question_index');
        }

        return $this->renderForm('question/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/question/edit/{id<[0-9]+>}", name="app_question_edit", methods={"GET", "PUT"})
     * @IsGranted("MANAGE_QUESTION", subject="question")
     */
    public function edit(Request $request, Question $question): Response
    {
        if (null == $question) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(QuestionType::class, $question, [
            'method' => 'PUT'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->updateQuestion();

            return $this->redirectToRoute('app_question_show', [
                'id' => $question->getId(),
                'slug' => $question->getSlug(),
            ]);
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/question/delete/{id<[0-9]+>}", name="app_question_delete", methods={"GET", "DELETE"})
     * @IsGranted("MANAGE_QUESTION", subject="question")
     */
    public function delete(Request $request, Question $question): Response
    {
        if (null == $question) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('question.delete' . $question->getId(), (string)$request->request->get('csrf_token'))) {
            $this->service->deleteQuestion($question);
        }

        return $this->redirectToRoute('app_question_index');
    }
}
