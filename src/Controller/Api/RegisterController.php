<?php

namespace App\Controller\Api;

use App\Entity\Factory\UserFactory;
use App\Exception\FormException;
use App\Form\UserType;

use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;

use Symfony\Component\{
    HttpFoundation\Request,
    HttpFoundation\Response,
    Routing\Annotation\Route
};

class RegisterController extends AbstractFOSRestController
{
    #[Route('/api/register', name: 'app_api_register', methods: 'POST')]
    public function addUser(Request $request, UserService $userService): Response
    {
        $user = UserFactory::create();
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->registerUser($user);

            $view = $this->view($user, Response::HTTP_OK);

            return $this->handleView($view);
        }

        throw new FormException($form, Response::HTTP_BAD_REQUEST);
    }
}
