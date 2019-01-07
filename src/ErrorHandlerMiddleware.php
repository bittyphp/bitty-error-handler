<?php

namespace Bitty\Middleware;

use Bitty\Http\Exception\HttpException;
use Bitty\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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
    public function handleException(\Exception $exception): ResponseInterface
    {
        if ($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getCode());
        }

        // TODO: handle exceptions based on config?

        return new Response('', 500);
    }
}
