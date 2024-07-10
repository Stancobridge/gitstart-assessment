<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class JsonExceptionListener
{
  public function onKernelException(ExceptionEvent $event)
  {
    $exception = $event->getThrowable();
    $response = new JsonResponse();

    $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR;

    $response->setStatusCode($statusCode);


    $response->setData([
      'error' => $exception->getMessage(),
      'code' => $statusCode
    ]);

    $event->setResponse($response);
  }
}
