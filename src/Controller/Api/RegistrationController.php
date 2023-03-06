<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\{EmailVerifyService, UserRegistrationService};
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\{HttpFoundation\JsonResponse,
    HttpFoundation\Request,
    HttpFoundation\Response,
    Routing\Annotation\Route,
    Validator\Constraints\Email,
    Validator\Validator\ValidatorInterface
};

class RegistrationController extends AbstractFOSRestcontroller
{
    private EmailVerifyService $emailVerifier;

    private EmailVerifier $emailVerifierSecurity;

    public function __construct(EmailVerifyService $emailVerifier, EmailVerifier $emailVerifierSecurity)
    {
        $this->emailVerifier = $emailVerifier;
        $this->emailVerifierSecurity = $emailVerifierSecurity;
    }

    #[Route('/api/register', name: 'app_register')]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        UserRegistrationService $userRegistrationService
    ): JsonResponse {
        if ($request->getMethod() !== 'POST') {
            return $this->json([
                'message' => 'Only POST requests accepted',
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        $errors = $validator->validate($data['email'], [
            new Email(),
        ]);

        if (count($errors) > 0) {
            return $this->json([
                'message' => (string)$errors,
            ], Response::HTTP_PARTIAL_CONTENT);
        }

        //TODO: Create validation for password

        if ($userRegistrationService->checkUniqUser($data['email'])) {
            return $this->json([
                'message' => 'User Already exists',
            ], Response::HTTP_CONFLICT);
        }

        $user = $userRegistrationService->registerUser($data['email'], $data['password']);
        $this->emailVerifier->verifyEmail($user);

        return $this->json([
            'message' => 'Registered!',
        ], Response::HTTP_OK);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository
    ): Response {
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
