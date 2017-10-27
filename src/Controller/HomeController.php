<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController implements Controller
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handleRequest(Request $request): Response
    {
        return new Response($this->twig->render('home/index.twig'));
    }
}
