<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
