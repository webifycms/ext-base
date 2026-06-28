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

namespace Webify\Base\Test\Unit\Infrastructure\Container;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Container\PhpDiContainerBuilder;
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Exception\ApplicationException;
use Webify\Base\Infrastructure\Service\{Application, Config};
use Webify\Base\Test\Unit\Infrastructure\Container\Example\{ExampleExtension, ExampleServiceProvider};

/**
 * PhpDiContainerBuilderTest tests the functionality of the PhpDiContainerBuilder class.
 *
 * @internal
 */
#[CoversClass(PhpDiContainerBuilder::class)]
#[CoversMethod(PhpDiContainerBuilder::class, 'build')]
#[UsesClass(Application::class)]
#[UsesClass(ApplicationException::class)]
#[UsesClass(Config::class)]
#[UsesClass(Environment::class)]
final class PhpDiContainerBuilderTest extends TestCase
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
	 * Test that build returns a PSR-11 container instance.
	 */
	#[Test]
	public function testBuildReturnsContainer(): void
	{
		$app       = $this->createApplication();
		$builder   = new PhpDiContainerBuilder();
		$container = $builder->build($app);

		self::assertInstanceOf(ContainerInterface::class, $container);
	}

	/**
	 * Test that build makes ConfigInterface available in the container.
	 */
	#[Test]
	public function testBuildMakesConfigAvailable(): void
	{
		$app       = $this->createApplication();
		$builder   = new PhpDiContainerBuilder();
		$container = $builder->build($app);

		self::assertTrue($container->has(ConfigInterface::class));
		self::assertSame($app->getConfig(), $container->get(ConfigInterface::class));
	}

	/**
	 * Test that build makes Environment available in the container.
	 */
	#[Test]
	public function testBuildMakesEnvironmentAvailable(): void
	{
		$app       = $this->createApplication();
		$builder   = new PhpDiContainerBuilder();
		$container = $builder->build($app);

		self::assertTrue($container->has(Environment::class));
		self::assertSame($app->getEnvironment(), $container->get(Environment::class));
	}

	/**
	 * Test that build registers provider definitions from the config.
	 */
	#[Test]
	public function testBuildRegistersProviderDefinitions(): void
	{
		$app = $this->createApplication([
			'providers' => [
				ExampleServiceProvider::class,
			],
		]);
		$builder   = new PhpDiContainerBuilder();
		$container = $builder->build($app);

		self::assertTrue($container->has('example.key'));
		self::assertSame('example.value', $container->get('example.key'));
	}

	/**
	 * Test that build registers extension provider definitions.
	 */
	#[Test]
	public function testBuildRegistersExtensionProviderDefinitions(): void
	{
		$app = $this->createApplication([
			'extensions' => [
				ExampleExtension::class,
			],
		]);
		$builder   = new PhpDiContainerBuilder();
		$container = $builder->build($app);

		self::assertTrue($container->has('extension.key'));
		self::assertSame('extension.value', $container->get('extension.key'));
	}

	/**
	 * Test that build enables compilation in production mode.
	 */
	#[Test]
	public function testBuildEnablesCompilationInProduction(): void
	{
		$app       = $this->createApplication([], 'production');
		$builder   = new PhpDiContainerBuilder();
		$container = $builder->build($app);

		self::assertInstanceOf(ContainerInterface::class, $container);
	}

	/**
	 * Create an application instance.
	 *
	 * @param array<string, mixed> $extraConfig
	 */
	private function createApplication(array $extraConfig = [], string $env = 'development'): Application
	{
		$config = new Config(array_merge([
			'basePath'    => '/app',
			'runtimePath' => $this->tempRuntimePath,
			'configPath'  => '/app/config',
		], $extraConfig));
		$envConfig = self::createStub(ConfigInterface::class);
		$envConfig->method('has')->willReturn(true);
		$envConfig->method('get')->willReturnCallback(
			function (string $key, mixed $default = null) use ($env): mixed {
				return match ($key) {
					'environment' => $env,
					'debug'       => false,
					default       => $default,
				};
			}
		);

		return new Application($config, Environment::prepare($envConfig));
	}

	/**
	 * Recursively remove a directory and its contents.
	 */
	private function removeDirectory(string $path): void
	{
		if (!is_dir($path)) {
			return;
		}

		$dirContents = scandir($path);
		$items       = is_array($dirContents) ? $dirContents : [];
		$items       = array_diff($items, ['.', '..']);

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
