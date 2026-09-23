<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Test\Unit\Infrastructure\Service;

use DI\ContainerBuilder;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\{ServiceProviderInterface, ThemeInterface, ThemeResolverInterface};
use Webify\Base\Infrastructure\Environment\{Environment, Type};
use Webify\Base\Infrastructure\Service\{Application, Config, ThemeLoader};
use Webify\Base\Test\Unit\Infrastructure\Theme\Example\{Active\ActiveTheme, Default\DefaultTheme};

use function is_dir;
use function mkdir;
use function rmdir;
use function scandir;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

/**
 * ThemeLoaderTest tests the functionality of the ThemeLoader service.
 *
 * @internal
 */
#[CoversClass(ThemeLoader::class)]
#[CoversMethod(ThemeLoader::class, 'load')]
#[UsesClass(Application::class)]
#[UsesClass(Config::class)]
#[UsesClass(Environment::class)]
#[UsesClass(Type::class)]
#[UsesClass(ContainerBuilder::class)]
#[UsesClass(ServiceProviderInterface::class)]
#[UsesClass(ThemeInterface::class)]
#[UsesClass(DefaultTheme::class)]
#[UsesClass(ActiveTheme::class)]
final class ThemeLoaderTest extends TestCase
{
	/**
	 * Temporary runtime path for testing.
	 */
	private string $tempDir;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->tempDir = sys_get_temp_dir() . '/webify_theme_loader_' . uniqid('', true);

		mkdir($this->tempDir . '/runtime', 0o755, true);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		$this->removeDirectory($this->tempDir);
	}

	/**
	 * Test that load registers the active theme provider and container bindings.
	 */
	#[Test]
	public function testLoadRegistersActiveThemeProviderAndBindings(): void
	{
		$activeTheme = new ActiveTheme();
		$resolver    = $this->createMock(ThemeResolverInterface::class);
		$resolver->expects(self::once())->method('default')->willReturn(new DefaultTheme());
		$resolver->expects(self::once())->method('active')->willReturn($activeTheme);

		$app     = $this->createApplication();
		$builder = new ContainerBuilder();

		new ThemeLoader($resolver)->load($app, $builder);
		$container = $builder->build();

		self::assertCount(1, $app->getProviders());
		self::assertInstanceOf(ServiceProviderInterface::class, $app->getProviders()[0]);
		self::assertSame(['active.definition' => 'active-value'], $app->getProviders()[0]->getDefinitions());
		self::assertSame($resolver, $container->get(ThemeResolverInterface::class));
		self::assertSame($activeTheme, $container->get(ThemeInterface::class));
		self::assertSame('active-value', $container->get('active.definition'));
	}

	/**
	 * Test that load skips registration when the active theme matches the default.
	 */
	#[Test]
	public function testLoadSkipsRegistrationWhenActiveSameAsDefault(): void
	{
		$default  = new DefaultTheme();
		$resolver = $this->createMock(ThemeResolverInterface::class);
		$resolver->expects(self::once())->method('default')->willReturn($default);
		$resolver->expects(self::once())->method('active')->willReturn($default);

		$app     = $this->createApplication();
		$builder = new ContainerBuilder();

		new ThemeLoader($resolver)->load($app, $builder);
		$container = $builder->build();

		self::assertCount(0, $app->getProviders());
		self::assertSame($default, $container->get(ThemeInterface::class));
	}

	/**
	 * Test that load does not fail when neither theme provides a service provider.
	 */
	#[Test]
	public function testLoadDoesNotFailWithoutProviders(): void
	{
		$default  = new DefaultTheme();
		$active   = self::createStub(ThemeInterface::class);
		$resolver = $this->createMock(ThemeResolverInterface::class);
		$resolver->expects(self::once())->method('default')->willReturn($default);
		$resolver->expects(self::once())->method('active')->willReturn($active);

		$app     = $this->createApplication();
		$builder = new ContainerBuilder();

		new ThemeLoader($resolver)->load($app, $builder);
		$container = $builder->build();

		self::assertCount(0, $app->getProviders());
		self::assertSame($resolver, $container->get(ThemeResolverInterface::class));
		self::assertSame($active, $container->get(ThemeInterface::class));
	}

	/**
	 * Create a real application instance backed by a temporary runtime path.
	 */
	private function createApplication(): Application
	{
		$config = new Config([
			'basePath'    => $this->tempDir,
			'runtimePath' => $this->tempDir . '/runtime',
			'configPath'  => $this->tempDir . '/config',
		]);
		$envConfig = self::createStub(ConfigInterface::class);
		$envConfig->method('has')->willReturn(true);
		$envConfig->method('get')->willReturnCallback(function (string $key, mixed $default = null): mixed {
			return match ($key) {
				'environment' => 'development',
				'debug'       => false,
				default       => $default,
			};
		});

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
