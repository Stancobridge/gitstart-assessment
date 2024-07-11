<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiController extends AbstractController
{
  /**
   * 
   *
   * @param string $message
   * @param mixed $data
   * @param int $statusCode
   * @param mixed[] $headers
   * @return JsonResponse
   */
  protected function transformResponse(
    string $message,
    mixed $data = [],
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
