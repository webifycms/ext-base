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

namespace Webify\Base\Infrastructure\Kernel;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Http\Message\ResponseFactoryInterface;

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
	 *
	 * @param Router                        $router               the router instance
	 * @param ServerRequestCreatorInterface $serverRequestCreator the server request creator instance
	 * @param EmitterInterface              $emitter              the emitter instance
	 * @param ResponseFactoryInterface      $responseFactory      the response factory instance
	 */
	public function __construct(
		private Router $router,
		private ServerRequestCreatorInterface $serverRequestCreator,
		private EmitterInterface $emitter,
		private ResponseFactoryInterface $responseFactory
	) {}

	/**
	 * Handles the HTTP request/response lifecycle.
	 */
	public function handle(): void
	{
		$request  = $this->serverRequestCreator->fromGlobals();

		try {
			$response = $this->router->dispatch($request);
		} catch (NotFoundException) {
			$response = $this->responseFactory
				->createResponse(302)
				->withHeader('Location', '/404')
			;
		}

		$this->emitter->emit($response);
	}
}
