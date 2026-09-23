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
use Webify\Base\Domain\ValueObject\{Hash, TemplatePath};
use Webify\Base\Infrastructure\Service\{ThemeAssetsProvider, ThemeConfig};
use Webify\Base\Infrastructure\Theme\Theme;

use function is_dir;
use function mkdir;
use function rmdir;
use function scandir;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

/**
 * ThemeTest tests the functionality of the Theme class.
 *
 * @internal
 */
#[CoversClass(Theme::class)]
#[CoversMethod(Theme::class, 'getId')]
#[CoversMethod(Theme::class, 'getName')]
#[CoversMethod(Theme::class, 'getVersion')]
#[CoversMethod(Theme::class, 'getConfig')]
#[CoversMethod(Theme::class, 'getRootPath')]
#[CoversMethod(Theme::class, 'getTemplatePath')]
#[CoversMethod(Theme::class, 'getTemplateMap')]
#[CoversMethod(Theme::class, 'getProvider')]
#[CoversMethod(Theme::class, 'getAssetsProvider')]
#[UsesClass(ThemeConfig::class)]
#[UsesClass(ThemeAssetsProvider::class)]
#[UsesClass(TemplatePath::class)]
#[UsesClass(Hash::class)]
final class ThemeTest extends TestCase
{
	/**
	 * Temporary directory hosting the theme root.
	 */
	private string $tempDir;

	/**
	 * The theme root path.
	 */
	private string $rootPath;

	/**
	 * The theme under test.
	 */
	private Theme $theme;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->tempDir  = sys_get_temp_dir() . '/webify_theme_' . uniqid('', true);
		$this->rootPath = $this->tempDir . '/theme';

		mkdir($this->rootPath, 0o755, true);

		$this->theme = new Theme(
			new ThemeConfig([
				'id'        => 'mytheme',
				'name'      => 'My Theme',
				'version'   => '1.2.3',
				'templates' => [
					'blog'  => 'blog/post',
					'admin' => '/abs/path',
				],
			]),
			$this->rootPath
		);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		$this->removeDirectory($this->tempDir);
	}

	/**
	 * Test that the theme identity is exposed.
	 */
	#[Test]
	public function testGetIdNameAndVersion(): void
	{
		self::assertSame('mytheme', $this->theme->getId());
		self::assertSame('My Theme', $this->theme->getName());
		self::assertSame('1.2.3', $this->theme->getVersion());
	}

	/**
	 * Test that a missing name defaults to the theme id.
	 */
	#[Test]
	public function testGetNameDefaultsToId(): void
	{
		$theme = $this->createTheme(['id' => 'mytheme']);

		self::assertSame('mytheme', $theme->getName());
	}

	/**
	 * Test that a missing version defaults to 0.0.0.
	 */
	#[Test]
	public function testGetVersionDefaultsToZeroZeroZero(): void
	{
		$theme = $this->createTheme(['id' => 'mytheme']);

		self::assertSame('0.0.0', $theme->getVersion());
	}

	/**
	 * Test that the config view exposes the raw manifest data.
	 */
	#[Test]
	public function testGetConfig(): void
	{
		$config = $this->theme->getConfig();

		self::assertInstanceOf(ThemeConfig::class, $config);
		self::assertSame('My Theme', $config->get('name'));
	}

	/**
	 * Test that the template path points at the templates directory under the root.
	 */
	#[Test]
	public function testGetTemplatePath(): void
	{
		self::assertSame($this->rootPath . '/templates', $this->theme->getTemplatePath()->toNative());
	}

	/**
	 * Test that relative manifest paths are anchored at the theme root.
	 */
	#[Test]
	public function testGetTemplateMapAnchorsRelativePaths(): void
	{
		$map = $this->theme->getTemplateMap();

		self::assertSame($this->rootPath . '/blog/post', $map['blog']->toNative());
		self::assertSame('/abs/path', $map['admin']->toNative());
	}

	/**
	 * Test that non-string template entries are skipped.
	 */
	#[Test]
	public function testSkipsNonStringTemplateEntries(): void
	{
		$theme = $this->createTheme([
			'templates' => [
				'blog'  => 'blog/post',
				'admin' => 123,
				'other' => null,
			],
		]);

		$map = $theme->getTemplateMap();

		self::assertCount(1, $map);
		self::assertSame($this->rootPath . '/blog/post', $map['blog']->toNative());
	}

	/**
	 * Test that a generic theme has no service provider.
	 */
	#[Test]
	public function testGetProviderIsNull(): void
	{
		self::assertNull($this->theme->getProvider());
	}

	/**
	 * Test that no assets provider is returned when the dist directory is missing.
	 */
	#[Test]
	public function testGetAssetsProviderIsNullWithoutDistDirectory(): void
	{
		self::assertNull($this->theme->getAssetsProvider());
	}

	/**
	 * Test that an assets provider is returned when the dist directory exists.
	 */
	#[Test]
	public function testGetAssetsProviderWhenDistDirectoryExists(): void
	{
		mkdir($this->rootPath . '/dist', 0o755, true);

		self::assertInstanceOf(ThemeAssetsProvider::class, $this->theme->getAssetsProvider());
	}

	/**
	 * Creates a theme from the given manifest data.
	 *
	 * @param array<string, mixed> $data the theme manifest data
	 */
	private function createTheme(array $data): Theme
	{
		return new Theme(new ThemeConfig($data), $this->rootPath);
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
