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
use Webify\Base\Domain\ValueObject\Hash;
use Webify\Base\Infrastructure\Service\ThemeAssetsProvider;
use Webify\Base\Test\Unit\Infrastructure\Theme\Example\Default\DefaultTheme;

use function dirname;

/**
 * ThemeAssetsProviderTest tests the functionality of the ThemeAssetsProvider service.
 *
 * @internal
 */
#[CoversClass(ThemeAssetsProvider::class)]
#[CoversMethod(ThemeAssetsProvider::class, 'getHash')]
#[CoversMethod(ThemeAssetsProvider::class, 'getDirectory')]
#[UsesClass(Hash::class)]
#[UsesClass(DefaultTheme::class)]
final class ThemeAssetsProviderTest extends TestCase
{
	/**
	 * Test that the hash matches the crc32 hash of the theme identity.
	 */
	#[Test]
	public function testGetHashMatchesThemeIdentityHash(): void
	{
		$provider = new ThemeAssetsProvider(new DefaultTheme());

		self::assertSame(
			Hash::generate('crc32', 'example-defaultExample Default Theme1.0.0')->toNative(),
			$provider->getHash()
		);
	}

	/**
	 * Test that the directory points to the theme dist directory.
	 */
	#[Test]
	public function testGetDirectoryPointsToDist(): void
	{
		$provider = new ThemeAssetsProvider(new DefaultTheme());

		self::assertSame(dirname(__DIR__) . '/Theme/Example/Default/dist', $provider->getDirectory());
	}

	/**
	 * Test that the generated hash is deterministic across instances.
	 */
	#[Test]
	public function testHashIsDeterministic(): void
	{
		$first  = new ThemeAssetsProvider(new DefaultTheme());
		$second = new ThemeAssetsProvider(new DefaultTheme());

		self::assertSame($first->getHash(), $second->getHash());
		self::assertSame($first->getDirectory(), $second->getDirectory());
	}
}
