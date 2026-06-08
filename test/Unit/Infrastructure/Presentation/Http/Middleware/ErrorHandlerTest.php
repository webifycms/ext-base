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

namespace Webify\Base\Test\Unit\Infrastructure\Presentation\Http\Middleware;

use League\Route\Http\Exception\NotFoundException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Presentation\Http\Middleware\ErrorHandler;

/**
 * ErrorHandlerTest tests the functionality of the ErrorHandler middleware.
 *
 * @internal
 */
#[CoversClass(ErrorHandler::class)]
#[CoversMethod(ErrorHandler::class, 'process')]
#[UsesClass(Environment::class)]
final class ErrorHandlerTest extends TestCase
{
	/**
	 * Test that in debug mode, the exception is re-thrown instead of being handled.
	 */
	#[Test]
	public function testProcessReThrowsInDebugMode(): void
	{
		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('debug error');

		$environment = $this->createDebugEnvironment();

		$handler = new ErrorHandler(
			self::createStub(LoggerInterface::class),
			new Psr17Factory(),
			$environment
		);

		$request  = self::createStub(ServerRequestInterface::class);
		$next     = $this->createMock(RequestHandlerInterface::class);
		$next->expects(self::once())->method('handle')->willThrowException(new RuntimeException('debug error'));

		$handler->process($request, $next);
	}

	/**
	 * Test that in production mode, a 500 response is returned for generic exceptions.
	 */
	#[Test]
	public function testProcessReturns500ResponseForGenericException(): void
	{
		$environment = $this->createProductionEnvironment();

		$handler = new ErrorHandler(
			self::createStub(LoggerInterface::class),
			new Psr17Factory(),
			$environment
		);

		$request  = self::createStub(ServerRequestInterface::class);
		$next     = $this->createMock(RequestHandlerInterface::class);
		$next->expects(self::once())->method('handle')->willThrowException(new RuntimeException('server error'));

		$response = $handler->process($request, $next);

		self::assertSame(500, $response->getStatusCode());
	}

	/**
	 * Test that in production mode, the status code from an HTTP exception is used.
	 */
	#[Test]
	public function testProcessReturnsHttpExceptionStatusCode(): void
	{
		$environment = $this->createProductionEnvironment();

		$handler = new ErrorHandler(
			self::createStub(LoggerInterface::class),
			new Psr17Factory(),
			$environment
		);

		$request  = self::createStub(ServerRequestInterface::class);
		$next     = $this->createMock(RequestHandlerInterface::class);
		$next->expects(self::once())->method('handle')->willThrowException(new NotFoundException());

		$response = $handler->process($request, $next);

		self::assertSame(404, $response->getStatusCode());
	}

	/**
	 * Test that the response has the correct content type header.
	 */
	#[Test]
	public function testProcessResponseHasCorrectContentType(): void
	{
		$environment = $this->createProductionEnvironment();

		$handler = new ErrorHandler(
			self::createStub(LoggerInterface::class),
			new Psr17Factory(),
			$environment
		);

		$request  = self::createStub(ServerRequestInterface::class);
		$next     = $this->createMock(RequestHandlerInterface::class);
		$next->expects(self::once())->method('handle')->willThrowException(new RuntimeException('error'));

		$response = $handler->process($request, $next);

		self::assertSame('text/html; charset=utf-8', $response->getHeaderLine('Content-Type'));
	}

	/**
	 * Test that 500-level errors are logged.
	 */
	#[Test]
	public function testProcessLogs500Errors(): void
	{
		$environment = $this->createProductionEnvironment();

		$logger = $this->createMock(LoggerInterface::class);
		$logger->expects(self::once())->method('error');

		$handler = new ErrorHandler(
			$logger,
			new Psr17Factory(),
			$environment
		);

		$request  = self::createStub(ServerRequestInterface::class);
		$next     = $this->createMock(RequestHandlerInterface::class);
		$next->expects(self::once())->method('handle')->willThrowException(new RuntimeException('server error'));

		$handler->process($request, $next);
	}

	/**
	 * Test that non-500 errors are not logged.
	 */
	#[Test]
	public function testProcessDoesNotLogNon500Errors(): void
	{
		$environment = $this->createProductionEnvironment();

		$logger = $this->createMock(LoggerInterface::class);
		$logger->expects(self::never())->method('error');

		$handler = new ErrorHandler(
			$logger,
			new Psr17Factory(),
			$environment
		);

		$request  = self::createStub(ServerRequestInterface::class);
		$next     = $this->createMock(RequestHandlerInterface::class);
		$next->expects(self::once())->method('handle')->willThrowException(new NotFoundException());

		$handler->process($request, $next);
	}

	/**
	 * Test that the handler passes the request through when no exception is thrown.
	 */
	#[Test]
	public function testProcessPassesThroughWhenNoException(): void
	{
		$environment = $this->createProductionEnvironment();

		$handler = new ErrorHandler(
			self::createStub(LoggerInterface::class),
			new Psr17Factory(),
			$environment
		);

		$response = new Psr17Factory()->createResponse(200);

		$request = self::createStub(ServerRequestInterface::class);
		$next    = $this->createMock(RequestHandlerInterface::class);
		$next->expects(self::once())->method('handle')->with($request)->willReturn($response);

		$result = $handler->process($request, $next);

		self::assertSame($response, $result);
	}

	private function createDebugEnvironment(): Environment
	{
		$envConfig = self::createStub(ConfigInterface::class);
		$envConfig->method('has')->willReturn(true);
		$envConfig->method('get')->willReturnCallback(function (string $key, mixed $default = null): mixed {
			return match ($key) {
				'environment' => 'development',
				'debug'       => true,
				default       => $default,
			};
		});

		return Environment::prepare($envConfig);
	}

	private function createProductionEnvironment(): Environment
	{
		$envConfig = self::createStub(ConfigInterface::class);
		$envConfig->method('has')->willReturn(true);
		$envConfig->method('get')->willReturnCallback(function (string $key, mixed $default = null): mixed {
			return match ($key) {
				'environment' => 'production',
				'debug'       => false,
				default       => $default,
			};
		});

		return Environment::prepare($envConfig);
	}
}
