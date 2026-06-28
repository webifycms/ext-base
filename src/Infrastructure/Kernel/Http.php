<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Kernel;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Webify\Base\Infrastructure\Contract\ErrorHandlerInterface;
use Webify\Base\Infrastructure\Environment\Environment;

/**
 * Http kernel handles the HTTP request/response lifecycle.
 *
 * Creates the server request from PHP globals, dispatches it through the middleware pipeline and router,
 * and returns emits the response to the client.
 */
final readonly class Http
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private Router $router,
		private ServerRequestCreatorInterface $serverRequestCreator,
		private EmitterInterface $emitter,
		private Environment $environment,
		private ErrorHandlerInterface $errorHandler,
		private LoggerInterface $logger
	) {}

	/**
	 * Handles the HTTP request/response lifecycle.
	 *
	 * In case of errors:
	 * - In debug mode it re-throws so the registered development error handler can produce a rich diagnostic page
	 * - In production, it logs the error and emits an appropriate HTTP error response using the error handler
	 *
	 * @throws Throwable
	 */
	public function handle(): void
	{
		$request  = $this->serverRequestCreator->fromGlobals();

		try {
			$response = $this->router->dispatch($request);
		} catch (Throwable $throwable) {
			$this->logger->error($throwable->getMessage(), [
				'exception' => $throwable,
				'file'      => $throwable->getFile(),
				'line'      => $throwable->getLine(),
				'uri'       => (string) $request->getUri(),
				'method'    => $request->getMethod(),
			]);

			if ($this->environment->isDebugEnabled() && !$throwable instanceof NotFoundException) {
				throw $throwable;
			}

			$response = $this->errorHandler->handle($request, $throwable);
		}

		$this->emitter->emit($response);
	}
}
