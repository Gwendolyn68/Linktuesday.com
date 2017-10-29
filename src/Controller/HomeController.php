<?php

namespace Controller;

use Service\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController implements Controller
{
    private $twig;
    private $linkService;

    public function __construct(\Twig_Environment $twig, Link $linkService)
    {
        $this->twig = $twig;
        $this->linkService = $linkService;
    }

    public function handleRequest(Request $request): Response
    {
        return new Response($this->twig->render('home/index.twig', [
            'recentLinks' => $this->linkService->getMostRecentLinks(),
        ]));
    }
}
