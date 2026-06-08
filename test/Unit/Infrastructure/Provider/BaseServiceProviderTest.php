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

namespace Webify\Base\Test\Unit\Infrastructure\Provider;

use League\Route\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\{BootstrapServiceProviderInterface, ServiceProviderInterface};
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Presentation\Http\Middleware\ErrorHandler;
use Webify\Base\Infrastructure\Provider\BaseServiceProvider;

/**
 * BaseServiceProviderTest tests the functionality of the BaseServiceProvider class.
 *
 * @internal
 */
#[CoversClass(BaseServiceProvider::class)]
#[CoversMethod(BaseServiceProvider::class, 'getDefinitions')]
#[CoversMethod(BaseServiceProvider::class, 'bootstrap')]
#[UsesClass(ErrorHandler::class)]
#[UsesClass(Environment::class)]
final class BaseServiceProviderTest extends TestCase
{
	/**
	 * Test that the provider implements both ServiceProviderInterface and BootstrapServiceProviderInterface.
	 */
	#[Test]
	public function testImplementsInterfaces(): void
	{
		$provider = new BaseServiceProvider();

		self::assertInstanceOf(ServiceProviderInterface::class, $provider);
		self::assertInstanceOf(BootstrapServiceProviderInterface::class, $provider);
	}

	/**
	 * Test that getDefinitions returns an array of definitions.
	 */
	#[Test]
	public function testGetDefinitionsReturnsArray(): void
	{
		$provider = new BaseServiceProvider();

		self::assertIsArray($provider->getDefinitions());
	}

	/**
	 * Test that bootstrap adds the ErrorHandler middleware to the router.
	 */
	#[Test]
	public function testBootstrapAddsErrorHandlerMiddleware(): void
	{
		$errorHandler = new ErrorHandler(
			self::createStub(LoggerInterface::class),
			new Psr17Factory(),
			$this->createEnvironment()
		);

		$router = $this->createMock(Router::class);
		$router->expects(self::once())->method('middleware')->with($errorHandler);

		$container = $this->createMock(ContainerInterface::class);
		$container->expects(self::exactly(2))->method('get')->willReturnMap([
			[Router::class, $router],
			[ErrorHandler::class, $errorHandler],
		]);

		$provider = new BaseServiceProvider();
		$provider->bootstrap($container);
	}

	private function createEnvironment(): Environment
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
