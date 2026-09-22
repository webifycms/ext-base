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

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Exception\ThemeException;
use Webify\Base\Infrastructure\Service\{Config, ThemeResolver};
use Webify\Base\Infrastructure\Theme\{Theme, ThemeFactory};
use Webify\Base\Test\Unit\Infrastructure\Theme\Example\{Active\ActiveTheme, Default\DefaultTheme};

use function dirname;
use function file_put_contents;
use function is_dir;
use function json_encode;
use function mkdir;
use function rmdir;
use function scandir;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

/**
 * ThemeResolverTest tests the functionality of the ThemeResolver service.
 *
 * @internal
 */
#[CoversClass(ThemeResolver::class)]
#[CoversMethod(ThemeResolver::class, 'active')]
#[CoversMethod(ThemeResolver::class, 'default')]
#[CoversMethod(ThemeResolver::class, 'themes')]
#[CoversMethod(ThemeResolver::class, 'themeTemplatePaths')]
#[CoversMethod(ThemeResolver::class, 'themeTemplateMaps')]
#[UsesClass(Config::class)]
#[UsesClass(Theme::class)]
#[UsesClass(ThemeFactory::class)]
#[UsesClass(ThemeException::class)]
#[UsesClass(TemplatePath::class)]
#[UsesClass(DefaultTheme::class)]
#[UsesClass(ActiveTheme::class)]
final class ThemeResolverTest extends TestCase
{
	/**
	 * Temporary directory used to host manifest driven themes.
	 */
	private string $tempDir;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->tempDir = sys_get_temp_dir() . '/webify_theme_resolver_' . uniqid('', true);

		mkdir($this->tempDir, 0o755, true);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		$this->removeDirectory($this->tempDir);
	}

	/**
	 * Test that the default theme class is resolved from the configuration.
	 */
	#[Test]
	public function testDefaultResolvesConfiguredThemeClass(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => ActiveTheme::class,
			],
		]);

		$theme = $resolver->default();

		self::assertInstanceOf(DefaultTheme::class, $theme);
		self::assertSame('example-default', $theme->getId());
		self::assertSame('Example Default Theme', $theme->getName());
		self::assertSame('1.0.0', $theme->getVersion());
	}

	/**
	 * Test that the active theme class is resolved from the configuration.
	 */
	#[Test]
	public function testActiveResolvesConfiguredThemeClass(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => ActiveTheme::class,
			],
		]);

		$theme = $resolver->active();

		self::assertInstanceOf(ActiveTheme::class, $theme);
		self::assertSame('example-active', $theme->getId());
		self::assertSame('Example Active Theme', $theme->getName());
		self::assertSame('2.0.0', $theme->getVersion());
	}

	/**
	 * Test that active falls back to the default theme when no active is configured.
	 */
	#[Test]
	public function testActiveFallsBackToDefault(): void
	{
		$resolver = $this->createResolver([
			'themes' => ['default' => DefaultTheme::class],
		]);

		self::assertSame('example-default', $resolver->active()->getId());
	}

	/**
	 * Test that active falls back to the default when the configured value is empty.
	 */
	#[Test]
	public function testActiveFallsBackWhenConfiguredValueIsEmpty(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => '',
			],
		]);

		self::assertSame('example-default', $resolver->active()->getId());
	}

	/**
	 * Test that active falls back to the default when the configured value is not a string.
	 */
	#[Test]
	public function testActiveFallsBackWhenConfiguredValueIsNotString(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => 42,
			],
		]);

		self::assertSame('example-default', $resolver->active()->getId());
	}

	/**
	 * Test that default throws when the theme configuration is absent.
	 */
	#[Test]
	public function testDefaultThrowsWhenNotConfigured(): void
	{
		$resolver = $this->createResolver([]);

		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('No default theme is configured in the application config.');

		$resolver->default();
	}

	/**
	 * Test that default throws when the configured value is empty.
	 */
	#[Test]
	public function testDefaultThrowsWhenConfiguredValueIsEmpty(): void
	{
		$resolver = $this->createResolver(['themes' => ['default' => '']]);

		$this->expectException(ThemeException::class);

		$resolver->default();
	}

	/**
	 * Test that default throws when the configured value is not a string.
	 */
	#[Test]
	public function testDefaultThrowsWhenConfiguredValueIsNotString(): void
	{
		$resolver = $this->createResolver(['themes' => ['default' => 42]]);

		$this->expectException(ThemeException::class);

		$resolver->default();
	}

	/**
	 * Test that the same default theme instance is returned on repeated calls.
	 */
	#[Test]
	public function testDefaultIsMemoized(): void
	{
		$resolver = $this->createResolver([
			'themes' => ['default' => DefaultTheme::class],
		]);

		self::assertSame($resolver->default(), $resolver->default());
	}

	/**
	 * Test that the same active theme instance is returned on repeated calls.
	 */
	#[Test]
	public function testActiveIsMemoized(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => ActiveTheme::class,
			],
		]);

		self::assertSame($resolver->active(), $resolver->active());
	}

	/**
	 * Test that themes returns the default first and the active last.
	 */
	#[Test]
	public function testThemesReturnsDefaultAndActive(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => ActiveTheme::class,
			],
		]);

		$themes = $resolver->themes();

		self::assertCount(2, $themes);
		self::assertSame('example-default', $themes[0]->getId());
		self::assertSame('example-active', $themes[1]->getId());
	}

	/**
	 * Test that themes returns a single entry when the active falls back to the default.
	 */
	#[Test]
	public function testThemesReturnsSingleWhenActiveSameAsDefault(): void
	{
		$resolver = $this->createResolver([
			'themes' => ['default' => DefaultTheme::class],
		]);

		$themes = $resolver->themes();

		self::assertCount(1, $themes);
		self::assertSame('example-default', $themes[0]->getId());
	}

	/**
	 * Test that themeTemplatePaths returns the template paths default first.
	 */
	#[Test]
	public function testThemeTemplatePaths(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => ActiveTheme::class,
			],
		]);

		self::assertSame(
			[
				$this->fixtureThemeRoot('Default') . '/templates',
				$this->fixtureThemeRoot('Active') . '/templates',
			],
			$resolver->themeTemplatePaths()
		);
	}

	/**
	 * Test that themeTemplateMaps returns the template maps default first.
	 */
	#[Test]
	public function testThemeTemplateMaps(): void
	{
		$resolver = $this->createResolver([
			'themes' => [
				'default' => DefaultTheme::class,
				'active'  => ActiveTheme::class,
			],
		]);

		$maps = $resolver->themeTemplateMaps();

		self::assertCount(2, $maps);
		self::assertSame($this->fixtureThemeRoot('Default') . '/blog', $maps[0]['blog']->toNative());
		self::assertSame($this->fixtureThemeRoot('Active') . '/blog', $maps[1]['blog']->toNative());
	}

	/**
	 * Test that a manifest theme id is resolved under the configured themes path.
	 */
	#[Test]
	public function testDefaultResolvesManifestThemeById(): void
	{
		$this->createManifestTheme('mytheme', [
			'id'      => 'mytheme',
			'name'    => 'My Theme',
			'version' => '1.2.3',
		]);

		$resolver = $this->createResolver([
			'basePath'  => $this->tempDir,
			'themes'    => ['default' => 'mytheme'],
		]);

		$theme = $resolver->default();

		self::assertInstanceOf(Theme::class, $theme);
		self::assertSame('mytheme', $theme->getId());
		self::assertSame('My Theme', $theme->getName());
		self::assertSame('1.2.3', $theme->getVersion());
		self::assertSame($this->tempDir . '/themes/mytheme', $theme->getRootPath());
	}

	/**
	 * Test that a manifest theme directory path is resolved directly.
	 */
	#[Test]
	public function testDefaultResolvesManifestThemeByPath(): void
	{
		$themeDir = $this->createManifestTheme('path-theme', [
			'id'      => 'path-theme',
			'name'    => 'Path Theme',
			'version' => '2.0.0',
		]);

		$resolver = $this->createResolver([
			'basePath'  => $this->tempDir,
			'themes'    => ['default' => $themeDir],
		]);

		$theme = $resolver->default();

		self::assertInstanceOf(Theme::class, $theme);
		self::assertSame('path-theme', $theme->getId());
		self::assertSame($themeDir, $theme->getRootPath());
	}

	/**
	 * Creates a theme resolver backed by a real config instance.
	 *
	 * @param array<string, mixed> $config the config data
	 */
	private function createResolver(array $config): ThemeResolver
	{
		return new ThemeResolver(new Config($config), new ThemeFactory(new Config($config)));
	}

	/**
	 * Returns the theme root of a fixture theme directory.
	 */
	private function fixtureThemeRoot(string $name): string
	{
		return dirname(__DIR__) . '/Theme/Example/' . $name;
	}

	/**
	 * Writes a manifest driven theme under the temporary themes directory.
	 *
	 * @param array<string, mixed> $data the manifest data
	 */
	private function createManifestTheme(string $id, array $data): string
	{
		$dir = $this->tempDir . '/themes/' . $id;

		mkdir($dir, 0o755, true);

		file_put_contents($dir . '/theme.json', (string) json_encode($data, JSON_UNESCAPED_SLASHES));

		return $dir;
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
