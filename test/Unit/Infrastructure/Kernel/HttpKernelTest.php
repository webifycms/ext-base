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

namespace Webify\Base\Test\Unit\Infrastructure\Kernel;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Webify\Base\Infrastructure\Kernel\Http;

/**
 * HttpKernelTest tests the functionality of the Http kernel.
 *
 * @internal
 */
#[CoversClass(Http::class)]
#[CoversMethod(Http::class, 'handle')]
final class HttpKernelTest extends TestCase
{
	/**
	 * Test that handle creates a request from globals, dispatches it, and emits the response.
	 */
	#[Test]
	public function testHandleDispatchesRequestAndEmitsResponse(): void
	{
		$request  = self::createStub(ServerRequestInterface::class);
		$response = new Psr17Factory()->createResponse(200);

		$requestCreator = $this->createMock(ServerRequestCreatorInterface::class);
		$requestCreator->expects(self::once())->method('fromGlobals')->willReturn($request);

		$router = $this->createMock(Router::class);
		$router->expects(self::once())->method('dispatch')->with($request)->willReturn($response);

		$emitter = $this->createMock(EmitterInterface::class);
		$emitter->expects(self::once())->method('emit')->with($response);

		$kernel = new Http($router, $requestCreator, $emitter, new Psr17Factory());
		$kernel->handle();
	}

	/**
	 * Test that handle catches NotFoundException and returns a 302 redirect to /404.
	 */
	#[Test]
	public function testHandleCatchesNotFoundExceptionAndRedirects(): void
	{
		$request  = self::createStub(ServerRequestInterface::class);

		$requestCreator = $this->createMock(ServerRequestCreatorInterface::class);
		$requestCreator->expects(self::once())->method('fromGlobals')->willReturn($request);

		$router = $this->createMock(Router::class);
		$router->expects(self::once())->method('dispatch')->with($request)->willThrowException(new NotFoundException());

		$emitter = $this->createMock(EmitterInterface::class);
		$emitter->expects(self::once())->method('emit')->with(
			self::callback(function (ResponseInterface $response): bool {
				return $response->getStatusCode() === 302
					&& $response->getHeaderLine('Location') === '/404';
			})
		);

		$kernel = new Http($router, $requestCreator, $emitter, new Psr17Factory());
		$kernel->handle();
	}
}
