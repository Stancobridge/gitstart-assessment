<?php

namespace App\EventListener;

use ReflectionClass;

use App\Exception\HttpValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class JsonExceptionListener
{
  public function onKernelException(ExceptionEvent $event)
  {
    $errorTimestamp = date('Y-m-d H:i:s');

    $exception = $event->getThrowable();
    $response = new JsonResponse();

    $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

    $response->setStatusCode($statusCode);

    $reflection = new ReflectionClass($exception::class);
    $exceptionClassName = $reflection->getShortName();

    $errorResponseData = [
      'message' => $exception->getMessage(),
      'statusCode' => $statusCode,
      'timestamp' => $errorTimestamp,
      'type' => $exceptionClassName
    ];

    if ($exception instanceof HttpValidationException) {
      $errorResponseData['errors'] = $exception->getErrorData();
    }

    if ($exception instanceof UnprocessableEntityHttpException) {

      $errorResponseData['message'] = 'Validation error occurred';
      $errorResponseData['errors'] = preg_split('/\r\n|\r|\n/', $exception->getMessage());
    }

    $response->setData($errorResponseData);

    $event->setResponse($response);
  }
}
