<?php
namespace App\Services;

use Infrastructure\Services\BaseService;
use Infrastructure\Services\ErrorHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Error extends BaseService implements ErrorHandlerInterface
{
    public function handle(\Infrastructure\Exceptions\HttpExceptionInterface $exception): Response
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'errorCode' => $exception->getErrorCode(),
            'errors' => $exception->getBody()
        ], $exception->getStatusCode(), $exception->getHeaders());
    }
}