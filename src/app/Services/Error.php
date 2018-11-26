<?php
namespace App\Services;

use Infrastructure\Application;
use Infrastructure\Exceptions\HttpExceptionInterface;
use Infrastructure\Services\BaseService;
use Infrastructure\Services\ErrorHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Error extends BaseService implements ErrorHandlerInterface
{
    public function handle(HttpExceptionInterface $exception): Response
    {
        return new JsonResponse([
            'message' => $this->getEnvDecoratedExceptionMessage($exception),
            'errorCode' => $exception->getErrorCode(),
            'errors' => $exception->getBody()
        ], $exception->getStatusCode(), $exception->getHeaders());
    }

    private function getEnvDecoratedExceptionMessage(HttpExceptionInterface $exception)
    {
        if ($exception->getStatusCode() >= Response::HTTP_INTERNAL_SERVER_ERROR && getenv('ENV') == Application::ENV_PROD) {
            return 'Internal server error';
        }

        return $exception->getMessage();
    }
}