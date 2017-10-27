<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorController implements Controller
{
    public function handleRequest(Request $request): Response
    {
        return new Response('Error');
    }
}
