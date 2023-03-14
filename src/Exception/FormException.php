<?php

namespace App\Exception;

use Exception;
use Symfony\Component\{
    Form\FormErrorIterator,
    Form\FormInterface,
    HttpKernel\Exception\HttpException
};

class FormException extends HttpException
{
    public function __construct(
        protected FormInterface $form,
        int $statusCode = 400,
        string $message = null,
        Exception $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getErrors(): FormErrorIterator
    {
        return $this->form->getErrors(true);
    }
}
