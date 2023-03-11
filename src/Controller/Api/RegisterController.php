<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'app_api_register', methods: 'POST')]
    public function index(Request $request): Response
    {
        dd($request->getContent());
        return $this->render('api/register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
}
