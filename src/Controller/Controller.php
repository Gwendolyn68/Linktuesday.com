<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface Controller
{
    public function handleRequest(Request $request): Response;
}
