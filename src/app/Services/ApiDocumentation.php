<?php

namespace App\Services;

use Infrastructure\Services\BaseService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiDocumentation extends BaseService
{
    public function generate()
    {
        $openapi = \OpenApi\scan(__DIR__ . '/../');
        $response = new JsonResponse( $openapi->jsonSerialize() );

        return $response;
    }
}