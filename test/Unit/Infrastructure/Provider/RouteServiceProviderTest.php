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
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\BootstrapServiceProviderInterface;
use Webify\Base\Infrastructure\Provider\RouteServiceProvider;
use Webify\Base\Infrastructure\Service\Config;

/**
 * RouteServiceProviderTest tests the functionality of the RouteServiceProvider class.
 *
 * @internal
 */
#[CoversClass(RouteServiceProvider::class)]
#[CoversMethod(RouteServiceProvider::class, 'bootstrap')]
final class RouteServiceProviderTest extends TestCase
{
	/**
	 * Test that the provider implements the BootstrapServiceProviderInterface.
	 */
	#[Test]
	public function testImplementsBootstrapServiceProviderInterface(): void
	{
		$provider = new RouteServiceProvider();

		self::assertInstanceOf(BootstrapServiceProviderInterface::class, $provider);
	}

	/**
	 * Test that bootstrap loads routes from the config path and registers them.
	 */
	#[Test]
	public function testBootstrapLoadsRoutes(): void
	{
		$router = $this->createMock(Router::class);
		$router->expects(self::once())->method('get');

		$config = new Config([
			'configPath'  => __DIR__ . '/Fixture',
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
		]);

		$container = $this->createMock(ContainerInterface::class);
		$container->expects(self::exactly(2))->method('get')->willReturnMap([
			[ConfigInterface::class, $config],
			[Router::class, $router],
		]);

		$provider = new RouteServiceProvider();
		$provider->bootstrap($container);
	}
}
