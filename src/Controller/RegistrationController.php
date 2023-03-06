<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\{
    EmailVerifyService,
    UserRegistrationService
};

use Symfony\Component\{
    HttpFoundation\Request,
    HttpFoundation\Response,
    Routing\Annotation\Route
};

class RegistrationController extends AbstractController
{
    private EmailVerifyService $emailVerifier;

    private EmailVerifier $emailVerifierSecurity;

    public function __construct(EmailVerifyService $emailVerifier, EmailVerifier $emailVerifierSecurity)
    {
        $this->emailVerifier = $emailVerifier;
        $this->emailVerifierSecurity = $emailVerifierSecurity;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserRegistrationService $userRegistrationService): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRegistrationService->registerUser($form->getData());
            $this->emailVerifier->verifyEmail($user);

            return $this->redirectToRoute('app_api_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->emailVerifierSecurity->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
