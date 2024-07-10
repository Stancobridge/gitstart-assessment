<?php

namespace App\Controller;

use App\Exception\HttpValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;

class ApiController extends AbstractController
{
  protected function transformResponse(
    string $message,
    $data = [],
    int $statusCode = JsonResponse::HTTP_OK,
    array $headers = []
  ): JsonResponse {

    $responseTimestamp = date('Y-m-d H:i:s');

    return new JsonResponse([
      'message' => $message,
      'statusCode' => $statusCode,
      'data' => $data,
      'timestamp' => $responseTimestamp
    ], $statusCode, $headers);
  }
}
