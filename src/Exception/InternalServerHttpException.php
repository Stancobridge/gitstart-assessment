<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalServerHttpException extends HttpException
{

  public function __construct($message, \Throwable $previous = null, array $headers = [], $code = 0)
  {

    parent::__construct(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $message, $previous, $headers, $code);
  }
}
