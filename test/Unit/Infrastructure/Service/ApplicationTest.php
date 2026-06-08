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

namespace Webify\Base\Test\Unit\Infrastructure\Service;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Container\ContainerBuilderInterface;
use Webify\Base\Infrastructure\Contract\{BootstrapServiceProviderInterface, ServiceProviderInterface};
use Webify\Base\Infrastructure\Environment\{Environment, Type};
use Webify\Base\Infrastructure\Exception\ApplicationException;
use Webify\Base\Infrastructure\Service\{Application, Config};
use Webify\Base\Test\Unit\Infrastructure\Service\Example\ExampleHttpKernelInterface;

/**
 * ApplicationTest tests the functionality of the Application class.
 *
 * @internal
 */
#[CoversClass(Application::class)]
#[CoversMethod(Application::class, '__construct')]
#[CoversMethod(Application::class, 'registerProvider')]
#[CoversMethod(Application::class, 'getContainer')]
#[CoversMethod(Application::class, 'getProviders')]
#[CoversMethod(Application::class, 'getConfig')]
#[CoversMethod(Application::class, 'getEnvironment')]
#[CoversMethod(Application::class, 'bootstrap')]
#[CoversMethod(Application::class, 'run')]
#[UsesClass(ApplicationException::class)]
#[UsesClass(Config::class)]
#[UsesClass(Environment::class)]
#[UsesClass(Type::class)]
#[UsesClass(ConfigInterface::class)]
final class ApplicationTest extends TestCase
{
	/**
	 * Temporary runtime path for testing.
	 */
	private string $tempRuntimePath;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->tempRuntimePath = sys_get_temp_dir() . '/webify_test_' . uniqid();
		mkdir($this->tempRuntimePath, 0o755, true);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		$this->removeDirectory($this->tempRuntimePath);
	}

	/**
	 * Test that the constructor throws when the base path is not defined in config.
	 */
	#[Test]
	public function testConstructorThrowsExceptionWhenBasePathNotDefined(): void
	{
		$this->expectException(ApplicationException::class);
		$this->expectExceptionMessage('Application base path not defined in the configurations.');

		$config = new Config([
			'basePath'    => '',
			'runtimePath' => $this->tempRuntimePath,
			'configPath'  => '/app/config',
		]);
		$environment = $this->createDevelopmentEnvironment();

		new Application($config, $environment);
	}

	/**
	 * Test that the constructor throws when the runtime path is not defined in config.
	 */
	#[Test]
	public function testConstructorThrowsExceptionWhenRuntimePathNotDefined(): void
	{
		$this->expectException(ApplicationException::class);
		$this->expectExceptionMessage('Application runtime path not defined in the configurations.');

		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '',
			'configPath'  => '/app/config',
		]);
		$environment = $this->createDevelopmentEnvironment();

		new Application($config, $environment);
	}

	/**
	 * Test that the constructor throws when the runtime path is not writable.
	 */
	#[Test]
	public function testConstructorThrowsExceptionWhenRuntimePathNotWritable(): void
	{
		$this->expectException(ApplicationException::class);
		$this->expectExceptionMessage('is not writable');

		$nonWritablePath = $this->tempRuntimePath . '/non_writable';
		mkdir($nonWritablePath, 0o444, true);

		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => $nonWritablePath,
			'configPath'  => '/app/config',
		]);
		$environment = $this->createDevelopmentEnvironment();

		try {
			new Application($config, $environment);
		} finally {
			chmod($nonWritablePath, 0o755);
		}
	}

	/**
	 * Test that getConfig returns the config instance passed to the constructor.
	 */
	#[Test]
	public function testGetConfig(): void
	{
		$config      = $this->createTestConfig();
		$environment = $this->createDevelopmentEnvironment();
		$application = new Application($config, $environment);

		self::assertSame($config, $application->getConfig());
	}

	/**
	 * Test that getEnvironment returns the environment instance passed to the constructor.
	 */
	#[Test]
	public function testGetEnvironment(): void
	{
		$config      = $this->createTestConfig();
		$environment = $this->createDevelopmentEnvironment();
		$application = new Application($config, $environment);

		self::assertSame($environment, $application->getEnvironment());
	}

	/**
	 * Test that registerProvider adds a provider to the application.
	 */
	#[Test]
	public function testRegisterProvider(): void
	{
		$config      = $this->createTestConfig();
		$environment = $this->createDevelopmentEnvironment();
		$application = new Application($config, $environment);

		$provider = self::createStub(ServiceProviderInterface::class);
		$application->registerProvider($provider);

		self::assertCount(1, $application->getProviders());
		self::assertSame($provider, $application->getProviders()[0]);
	}

	/**
	 * Test that registerProvider accepts multiple providers of different types.
	 */
	#[Test]
	public function testRegisterMultipleProviders(): void
	{
		$config      = $this->createTestConfig();
		$environment = $this->createDevelopmentEnvironment();
		$application = new Application($config, $environment);

		$provider1 = self::createStub(ServiceProviderInterface::class);
		$provider2 = self::createStub(BootstrapServiceProviderInterface::class);
		$application->registerProvider($provider1);
		$application->registerProvider($provider2);

		self::assertCount(2, $application->getProviders());
	}

	/**
	 * Test that bootstrap builds the DI container and makes it accessible.
	 */
	#[Test]
	public function testBootstrapBuildsContainer(): void
	{
		$config      = $this->createTestConfig();
		$environment = $this->createDevelopmentEnvironment();
		$application = new Application($config, $environment);

		$container        = self::createStub(ContainerInterface::class);
		$containerBuilder = $this->createMock(ContainerBuilderInterface::class);
		$containerBuilder->expects(self::once())->method('build')->with($application)->willReturn($container);

		$application->bootstrap($containerBuilder);

		self::assertSame($container, $application->getContainer());
	}

	/**
	 * Test that bootstrap calls the bootstrap method on BootstrapServiceProviderInterface providers.
	 */
	#[Test]
	public function testBootstrapCallsBootstrapOnBootstrapProviders(): void
	{
		$config      = $this->createTestConfig();
		$environment = $this->createDevelopmentEnvironment();
		$application = new Application($config, $environment);

		$bootstrapProvider = $this->createMock(BootstrapServiceProviderInterface::class);
		$bootstrapProvider->expects(self::once())->method('bootstrap');
		$application->registerProvider($bootstrapProvider);

		$regularProvider = self::createStub(ServiceProviderInterface::class);
		$application->registerProvider($regularProvider);

		$container        = self::createStub(ContainerInterface::class);
		$containerBuilder = $this->createMock(ContainerBuilderInterface::class);
		$containerBuilder->expects(self::once())->method('build')->with($application)->willReturn($container);

		$application->bootstrap($containerBuilder);
	}

	/**
	 * Test that run gets the HTTP kernel from the container and invokes its handle method.
	 */
	#[Test]
	public function testRunGetsHttpKernelAndHandles(): void
	{
		$config      = $this->createTestConfig();
		$environment = $this->createDevelopmentEnvironment();
		$application = new Application($config, $environment);

		$httpKernel = $this->createMock(ExampleHttpKernelInterface::class);
		$httpKernel->expects(self::once())->method('handle');

		$container = $this->createMock(ContainerInterface::class);
		$container->expects(self::once())->method('get')->with('httpKernel')->willReturn($httpKernel);

		$containerBuilder = $this->createMock(ContainerBuilderInterface::class);
		$containerBuilder->expects(self::once())->method('build')->with($application)->willReturn($container);

		$application->bootstrap($containerBuilder);
		$application->run();
	}

	/**
	 * Test that the constructor creates the cache and log runtime directories.
	 */
	#[Test]
	public function testConstructorCreatesRuntimeDirectories(): void
	{
		$customRuntimePath = $this->tempRuntimePath . '/custom_runtime';
		mkdir($customRuntimePath, 0o755, true);

		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => $customRuntimePath,
			'configPath'  => '/app/config',
		]);
		$environment = $this->createDevelopmentEnvironment();

		new Application($config, $environment);

		self::assertDirectoryExists($customRuntimePath . '/cache');
		self::assertDirectoryExists($customRuntimePath . '/log');

		rmdir($customRuntimePath . '/cache');
		rmdir($customRuntimePath . '/log');
		rmdir($customRuntimePath);
	}

	/**
	 * Create a test config with default values.
	 */
	private function createTestConfig(): Config
	{
		return new Config([
			'basePath'    => '/app',
			'runtimePath' => $this->tempRuntimePath,
			'configPath'  => '/app/config',
		]);
	}

	/**
	 * Create a development environment with default values.
	 */
	private function createDevelopmentEnvironment(): Environment
	{
		$envConfig = self::createStub(ConfigInterface::class);
		$envConfig->method('has')->willReturn(true);
		$envConfig->method('get')->willReturnCallback(function (string $key, mixed $default = null): mixed {
			return match ($key) {
				'environment' => 'development',
				'debug'       => false,
				default       => $default,
			};
		});

		return Environment::prepare($envConfig);
	}

	/**
	 * Recursively remove a directory and its contents.
	 */
	private function removeDirectory(string $path): void
	{
		if (!is_dir($path)) {
			return;
		}

		$scandir = scandir($path);
		$items   = array_diff(false === $scandir ? [] : $scandir, ['.', '..']);

		foreach ($items as $item) {
			$itemPath = $path . '/' . $item;

			if (is_dir($itemPath)) {
				$this->removeDirectory($itemPath);
			} else {
				unlink($itemPath);
			}
		}

		rmdir($path);
	}
}
