<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Presentation\Http\Middleware;

use League\Route\Http\Exception\HttpExceptionInterface;
use Psr\Http\Message\{ResponseFactoryInterface, ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Psr\Log\LoggerInterface;
use Throwable;
use Webify\Base\Infrastructure\Environment\Environment;

/**
 * Catches all uncaught exceptions within the middleware pipeline.
 *
 * In debug mode it re-throws so the registered development error handler
 * (Whoops) can produce a rich diagnostic page. In production, it logs the
 * exception and emits an appropriate HTTP error response.
 */
final readonly class ErrorHandler implements MiddlewareInterface
{
	/**
	 * The constructor.
	 *
	 * @param LoggerInterface          $logger          the logger instance
	 * @param ResponseFactoryInterface $responseFactory the response factory instance
	 * @param Environment              $environment     the environment instance
	 */
	public function __construct(
		private LoggerInterface $logger,
		private ResponseFactoryInterface $responseFactory,
		private Environment $environment
	) {}

	/**
	 * {@inheritDoc}
	 *
	 * @throws Throwable
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		try {
			return $handler->handle($request);
		} catch (Throwable $throwable) {
			if ($this->environment->isDebugEnabled()) {
				throw $throwable;
			}

			return $this->handleThrowable($throwable, $request);
		}
	}

	/**
	 * Handles a throwable and returns an appropriate HTTP response and logs the error.
	 */
	private function handleThrowable(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
	{
		$statusCode = $throwable instanceof HttpExceptionInterface ? $throwable->getStatusCode() : 500;

		if (500 <= $statusCode) {
			$this->logger->error(
				$throwable->getMessage(),
				[
					'exception' => $throwable,
					'file'      => $throwable->getFile(),
					'line'      => $throwable->getLine(),
					'uri'       => (string) $request->getUri(),
					'method'    => $request->getMethod(),
				]
			);
		}

		return $this->responseFactory
			->createResponse($statusCode)
			->withHeader('Content-Type', 'text/html; charset=utf-8')
		;
	}
}
