<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    #[Route(
        '/hello/{name}',
        name: 'hello_index',
        requirements: ['name' => '[A-Za-z]+'],
        defaults: ['name' => 'World'],
        methods: 'GET'
    )]
    public function index(string $name): Response
    {

        return $this->render(
        'hello/index.html.twig',
        ['name' => $name]
    );
    }
}