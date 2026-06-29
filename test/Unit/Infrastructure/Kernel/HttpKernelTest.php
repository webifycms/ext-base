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

namespace Webify\Base\Test\Unit\Infrastructure\Kernel;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\ErrorHandlerInterface;
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Kernel\Http;

/**
 * HttpKernelTest tests the functionality of the Http kernel.
 *
 * @internal
 */
#[CoversClass(Http::class)]
#[CoversMethod(Http::class, 'handle')]
#[UsesClass(Environment::class)]
final class HttpKernelTest extends TestCase
{
	/**
	 * Test that handle creates a request from globals, dispatches it, and emits the response.
	 */
	#[Test]
	public function testHandleDispatchesRequestAndEmitsResponse(): void
	{
		$request        = self::createStub(ServerRequestInterface::class);
		$response       = new Psr17Factory()->createResponse(200);
		$requestCreator = $this->createMock(ServerRequestCreatorInterface::class);

		$requestCreator->expects(self::once())->method('fromGlobals')->willReturn($request);

		$router = $this->createMock(Router::class);

		$router->expects(self::once())->method('dispatch')->with($request)->willReturn($response);

		$emitter = $this->createMock(EmitterInterface::class);

		$emitter->expects(self::once())->method('emit')->with($response);

		$kernel = new Http(
			$router,
			$requestCreator,
			$emitter,
			$this->createEnvironment(false),
			self::createStub(ErrorHandlerInterface::class),
			self::createStub(LoggerInterface::class)
		);

		$kernel->handle();
	}

	/**
	 * Test that handle catches exceptions and uses the error handler in production mode.
	 */
	#[Test]
	public function testHandleCatchesExceptionAndUsesErrorHandler(): void
	{
		$request        = self::createStub(ServerRequestInterface::class);
		$response       = new Psr17Factory()->createResponse(500);
		$requestCreator = $this->createMock(ServerRequestCreatorInterface::class);

		$requestCreator->expects(self::once())->method('fromGlobals')->willReturn($request);

		$router = $this->createMock(Router::class);

		$router->expects(self::once())->method('dispatch')
			->with($request)
			->willThrowException(new NotFoundException())
		;

		$errorHandler = $this->createMock(ErrorHandlerInterface::class);
		$errorHandler->expects(self::once())->method('handle')
			->with($request, self::isInstanceOf(NotFoundException::class))
			->willReturn($response)
		;

		$emitter = $this->createMock(EmitterInterface::class);

		$emitter->expects(self::once())->method('emit')->with($response);

		$kernel = new Http(
			$router,
			$requestCreator,
			$emitter,
			$this->createEnvironment(false),
			$errorHandler,
			self::createStub(LoggerInterface::class)
		);

		$kernel->handle();
	}

	/**
	 * Test that debug mode re-throws non-NotFoundException exceptions.
	 */
	#[Test]
	public function testHandleRethrowsNonNotFoundExceptionInDebugMode(): void
	{
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Something went wrong');

		$request        = self::createStub(ServerRequestInterface::class);
		$requestCreator = $this->createMock(ServerRequestCreatorInterface::class);

		$requestCreator->expects(self::once())->method('fromGlobals')->willReturn($request);

		$router = $this->createMock(Router::class);

		$router->expects(self::once())->method('dispatch')
			->with($request)
			->willThrowException(new RuntimeException('Something went wrong'))
		;

		$kernel = new Http(
			$router,
			$requestCreator,
			self::createStub(EmitterInterface::class),
			$this->createEnvironment(true),
			self::createStub(ErrorHandlerInterface::class),
			self::createStub(LoggerInterface::class)
		);

		$kernel->handle();
	}

	/**
	 * Test that debug mode does not re-throw NotFoundException.
	 */
	#[Test]
	public function testHandleDoesNotRethrowNotFoundExceptionInDebugMode(): void
	{
		$request        = self::createStub(ServerRequestInterface::class);
		$response       = new Psr17Factory()->createResponse(404);
		$requestCreator = $this->createMock(ServerRequestCreatorInterface::class);

		$requestCreator->expects(self::once())->method('fromGlobals')->willReturn($request);

		$router = $this->createMock(Router::class);

		$router->expects(self::once())->method('dispatch')
			->with($request)
			->willThrowException(new NotFoundException())
		;

		$errorHandler = $this->createMock(ErrorHandlerInterface::class);
		$errorHandler->expects(self::once())->method('handle')
			->with($request, self::isInstanceOf(NotFoundException::class))
			->willReturn($response)
		;

		$emitter = $this->createMock(EmitterInterface::class);

		$emitter->expects(self::once())->method('emit')->with($response);

		$kernel = new Http(
			$router,
			$requestCreator,
			$emitter,
			$this->createEnvironment(true),
			$errorHandler,
			self::createStub(LoggerInterface::class)
		);

		$kernel->handle();
	}

	private function createEnvironment(bool $debug): Environment
	{
		$config = self::createStub(ConfigInterface::class);
		$config->method('has')->willReturn(true);
		$config->method('get')->willReturnCallback(function (string $key, mixed $default = null): mixed {
			return match ($key) {
				'environment' => 'production',
				'debug'       => true,
				default       => $default,
			};
		});

		return Environment::prepare($config);
	}
}
