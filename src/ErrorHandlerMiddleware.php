<?php

namespace Bitty\Middleware;

use Bitty\Http\Exception\HttpException;
use Bitty\Http\Response;
use Bitty\Middleware\MiddlewareInterface;
use Bitty\Middleware\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        try {
            return $handler->handle($request);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * Handles exceptions.
     *
     * @param \Exception $exception
     *
     * @return ResponseInterface
     */
    public function handleException(\Exception $exception)
    {
        if ($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getCode());
        }

        return new Response('', 500);
    }
}
