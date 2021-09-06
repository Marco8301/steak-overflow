<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionService
{
    private RequestStack $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function setFlashMessage(string $type, string $message): void
    {
        $session = $this->request->getSession();
        if (method_exists($session, 'getFlashBag')) {
            $session->getFlashBag()->add($type, $message);
        }
    }
}
