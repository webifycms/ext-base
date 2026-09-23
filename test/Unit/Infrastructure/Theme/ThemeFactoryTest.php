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

namespace Webify\Base\Test\Unit\Infrastructure\Theme;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Exception\ThemeException;
use Webify\Base\Infrastructure\Service\{Config, ThemeConfig};
use Webify\Base\Infrastructure\Theme\{Theme, ThemeFactory, ThemeManifestValidator};
use Webify\Base\Test\Unit\Infrastructure\Theme\Example\Default\DefaultTheme;

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
 * ThemeFactoryTest tests the functionality of the ThemeFactory class.
 *
 * @internal
 */
#[CoversClass(ThemeFactory::class)]
#[CoversMethod(ThemeFactory::class, 'fromConfig')]
#[UsesClass(Theme::class)]
#[UsesClass(ThemeConfig::class)]
#[UsesClass(ThemeException::class)]
#[UsesClass(ThemeManifestValidator::class)]
#[UsesClass(Config::class)]
#[UsesClass(DefaultTheme::class)]
final class ThemeFactoryTest extends TestCase
{
	/**
	 * Temporary directory acting as the application base path.
	 */
	private string $tempDir;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->tempDir = sys_get_temp_dir() . '/webify_theme_factory_' . uniqid('', true);

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
	 * Test that fromConfig resolves an existing theme directory path.
	 */
	#[Test]
	public function testFromConfigResolvesDirectoryPath(): void
	{
		$themeDir = $this->createTheme('mytheme');

		$theme = $this->createFactory()->fromConfig($themeDir);

		self::assertInstanceOf(Theme::class, $theme);
		self::assertSame('mytheme', $theme->getId());
		self::assertSame($themeDir, $theme->getRootPath());
	}

	/**
	 * Test that fromConfig resolves a theme id under the configured themes path.
	 */
	#[Test]
	public function testFromConfigResolvesThemeId(): void
	{
		$themeDir = $this->createTheme('mytheme');

		$theme = $this->createFactory()->fromConfig('mytheme');

		self::assertInstanceOf(Theme::class, $theme);
		self::assertSame('mytheme', $theme->getId());
		self::assertSame($themeDir, $theme->getRootPath());
	}

	/**
	 * Test that fromConfig resolves a configured theme class.
	 */
	#[Test]
	public function testFromConfigResolvesConfiguredClass(): void
	{
		$theme = $this->createFactory()->fromConfig(DefaultTheme::class);

		self::assertInstanceOf(DefaultTheme::class, $theme);
		self::assertSame('example-default', $theme->getId());
		self::assertSame('Example Default Theme', $theme->getName());
		self::assertSame('1.0.0', $theme->getVersion());
	}

	/**
	 * Test that a theme class resolves its configuration from the theme itself.
	 */
	#[Test]
	public function testFromConfigClassThemeResolvesItsOwnConfig(): void
	{
		$theme = $this->createFactory()->fromConfig(DefaultTheme::class);

		self::assertSame('example-default', $theme->getConfig()->get('id'));
	}

	/**
	 * Test that fromConfig throws when the configured class is not a theme.
	 */
	#[Test]
	public function testFromConfigThrowsWhenClassIsNotATheme(): void
	{
		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('must implement');

		$this->createFactory()->fromConfig(self::class);
	}

	/**
	 * Test that fromConfig throws when the theme manifest is missing.
	 */
	#[Test]
	public function testFromConfigThrowsWhenManifestMissing(): void
	{
		mkdir($this->tempDir . '/themes/nomanifest', 0o755, true);

		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Unable to read theme manifest from');

		$this->createFactory()->fromConfig('nomanifest');
	}

	/**
	 * Test that fromConfig throws when the manifest lacks an id.
	 */
	#[Test]
	public function testFromConfigThrowsWhenManifestIdMissing(): void
	{
		$dir = $this->tempDir . '/themes/noid';

		mkdir($dir, 0o755, true);
		file_put_contents($dir . '/theme.json', (string) json_encode(['name' => 'No Id Theme']));

		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->createFactory()->fromConfig('noid');
	}

	/**
	 * Test that fromConfig throws when the manifest lacks a name.
	 */
	#[Test]
	public function testFromConfigThrowsWhenManifestNameMissing(): void
	{
		$dir = $this->tempDir . '/themes/noname';

		mkdir($dir, 0o755, true);
		file_put_contents($dir . '/theme.json', (string) json_encode(['id' => 'noname']));

		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->createFactory()->fromConfig('noname');
	}

	/**
	 * Create a real config instance backed by the temporary base path.
	 */
	private function createConfig(): Config
	{
		return new Config([
			'basePath'    => $this->tempDir,
			'runtimePath' => $this->tempDir . '/runtime',
			'configPath'  => $this->tempDir . '/config',
		]);
	}

	/**
	 * Create a theme factory backed by a real config instance.
	 */
	private function createFactory(): ThemeFactory
	{
		return new ThemeFactory($this->createConfig());
	}

	/**
	 * Writes a manifest driven theme under the temporary themes directory.
	 */
	private function createTheme(string $id): string
	{
		$dir = $this->tempDir . '/themes/' . $id;

		mkdir($dir, 0o755, true);

		file_put_contents($dir . '/theme.json', (string) json_encode([
			'id'      => $id,
			'name'    => 'Example Theme',
			'version' => '1.0.0',
		], JSON_UNESCAPED_SLASHES));

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
