<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationHttpException extends HttpException
{
  /**
   *
   * @var string[] An array of errors.
   */
  private array $errorData;

  /**
   * 
   * @param string[] $data
   * @param \Throwable|null $previous
   * @param mixed[] $headers
   * @param integer $code
   */
  public function __construct(array $data, \Throwable $previous = null, array $headers = [], int $code = 0)
  {
    $this->errorData = $data;
    parent::__construct(JsonResponse::HTTP_BAD_REQUEST, 'Validation failed', $previous, $headers, $code);
  }

  /**
   * Returns an array of errors.
   *
   * @return string[] An array of errors.
   */
  public function getErrorData(): array
  {
    return $this->errorData;
  }
}
