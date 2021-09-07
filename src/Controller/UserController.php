<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\AnswerRepository;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/register", name="app_user_register", methods={"GET", "POST"})
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'register' => true
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->registerUser($user, $form->get('password')->getData());

            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm('user/register.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/account", name="app_user_account", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function account(AnswerRepository $answerRepository): Response
    {
        return $this->render('user/account.html.twig', [
            'validAnswers' => $answerRepository->findValidAnswers()
        ]);
    }

    /**
     * @Route("/account/edit", name="app_user_account_edit", methods={"GET", "PATCH"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function accountEdit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, [
            'method' => 'PATCH'
        ])
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->updateUser();

            return $this->redirectToRoute('app_user_account');
        }
        return $this->renderForm('user/edit.html.twig', [
            'form' => $form
        ]);
    }
}
