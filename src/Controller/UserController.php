<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="app_user_register", methods={"GET", "POST"})
     */
    public function register(Request $request, UserService $service): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'register' => true
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->registerUser($user, $form->get('password')->getData());

            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm('user/register.html.twig', [
            'form' => $form
        ]);
    }
}
