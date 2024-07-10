<?php

// src/Controller/ApiController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class ApiController extends AbstractController
{
  
  public function transformResponse(Request $request, RateLimiterFactory $anonymousApiLimiter): Response
  {
    $limiter = $anonymousApiLimiter->create($request->getClientIp());
    $limit = $limiter->consume();
    $headers = [
      'X-RateLimit-Remaining' => $limit->getRemainingTokens(),
      'X-RateLimit-Retry-After' => $limit->getRetryAfter()->getTimestamp() - time(),
      'X-RateLimit-Limit' => $limit->getLimit(),
    ];

    if (false === $limit->isAccepted()) {
      return new Response(null, Response::HTTP_TOO_MANY_REQUESTS, $headers);
    }

    $response = new JsonResponse();
    $response->headers->add($headers);

    return $response;
  }
}
