<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalServerHttpException extends HttpException
{

  /**
   * 
   * @param string $message
   * @param \Throwable|null $previous
   * @param mixed[] $headers
   * @param integer $code
   */
  public function __construct(string $message, \Throwable $previous = null, array $headers = [], int $code = 0)
  {

    parent::__construct(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $message, $previous, $headers, $code);
  }
}
