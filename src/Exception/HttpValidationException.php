<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpValidationException extends HttpException
{
  private $errorData;

  public function __construct(array $data, \Throwable $previous = null, array $headers = [], $code = 0)
  {
    $this->errorData = $data;
    parent::__construct(JsonResponse::HTTP_BAD_REQUEST, 'Validation failed', $previous, $headers, $code);
  }

  public function getErrorData(): array
  {
    return $this->errorData;
  }
}
