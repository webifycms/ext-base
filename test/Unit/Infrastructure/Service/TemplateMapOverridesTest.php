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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\{CacheItemInterface, CacheItemPoolInterface};
use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Contract\ThemeResolverInterface;
use Webify\Base\Infrastructure\Service\TemplateMapOverrides;

/**
 * TemplateMapOverridesTest tests the functionality of the TemplateMapOverrides service.
 *
 * @internal
 */
#[CoversClass(TemplateMapOverrides::class)]
#[CoversMethod(TemplateMapOverrides::class, 'forExtension')]
#[UsesClass(TemplatePath::class)]
final class TemplateMapOverridesTest extends TestCase
{
	/**
	 * Test that cached values are returned without rebuilding or saving.
	 *
	 * The theme maps are still consulted to compute the content-addressed key,
	 * but the assembled candidates are neither rebuilt nor re-saved.
	 */
	#[Test]
	public function testForExtensionReturnsCachedValuesOnHit(): void
	{
		$expected = [TemplatePath::fromString('/cached/templates/blog')];

		$item = self::createStub(CacheItemInterface::class);
		$item->method('isHit')->willReturn(true);
		$item->method('get')->willReturn($expected);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->method('getItem')->willReturn($item);
		$pool->expects(self::never())->method('save');

		$resolver = self::createStub(ThemeResolverInterface::class);
		$resolver->method('themeTemplateMaps')->willReturn([]);

		$overrides = new TemplateMapOverrides(['blog' => '/app/templates/blog'], $resolver, $pool);

		self::assertSame($expected, $overrides->forExtension('blog'));
	}

	/**
	 * Test that on a cache miss the overrides are ordered app first then theme maps.
	 */
	#[Test]
	public function testForExtensionBuildsOrderedCandidatesOnMiss(): void
	{
		/** @var CacheItemInterface&MockObject $item */
		$item = $this->createMock(CacheItemInterface::class);
		$item->method('isHit')->willReturn(false);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->method('getItem')->willReturn($item);

		$resolver = self::createStub(ThemeResolverInterface::class);
		$resolver->method('themeTemplateMaps')->willReturn([
			['blog' => TemplatePath::fromString('/theme/templates/blog')],
			['blog' => TemplatePath::fromString('/active/templates/blog')],
		]);

		$expected = [
			TemplatePath::fromString('/app/templates/blog'),
			TemplatePath::fromString('/theme/templates/blog'),
			TemplatePath::fromString('/active/templates/blog'),
		];

		$item->expects(self::once())->method('set')->with($expected);
		$pool->expects(self::once())->method('save')->with($item);

		$overrides = new TemplateMapOverrides(['blog' => '/app/templates/blog'], $resolver, $pool);

		$result = $overrides->forExtension('blog');

		self::assertCount(3, $result);
		self::assertSame('/app/templates/blog', $result[0]->toNative());
		self::assertSame('/theme/templates/blog', $result[1]->toNative());
		self::assertSame('/active/templates/blog', $result[2]->toNative());
	}

	/**
	 * Test that the cache key changes when the app override for the extension changes.
	 */
	#[Test]
	public function testCacheKeyTracksAppOverrides(): void
	{
		$keys = [];

		$item = self::createStub(CacheItemInterface::class);

		$pool = self::createStub(CacheItemPoolInterface::class);
		$pool->method('getItem')
			->willReturnCallback(static function (string $key) use ($item, &$keys): CacheItemInterface {
				$keys[] = $key;

				return $item;
			})
		;

		$resolver = self::createStub(ThemeResolverInterface::class);
		$resolver->method('themeTemplateMaps')->willReturn([]);

		new TemplateMapOverrides(['blog' => '/app/templates/blog'], $resolver, $pool)->forExtension('blog');
		new TemplateMapOverrides(['blog' => '/custom/templates/blog'], $resolver, $pool)->forExtension('blog');

		self::assertCount(2, $keys);
		self::assertNotSame($keys[0], $keys[1]);
	}

	/**
	 * Test that the cache key changes when the theme template maps change.
	 */
	#[Test]
	public function testCacheKeyTracksThemeTemplateMaps(): void
	{
		$keys = [];

		$item = self::createStub(CacheItemInterface::class);

		$pool = self::createStub(CacheItemPoolInterface::class);
		$pool->method('getItem')
			->willReturnCallback(static function (string $key) use ($item, &$keys): CacheItemInterface {
				$keys[] = $key;

				return $item;
			})
		;

		$firstResolver = self::createStub(ThemeResolverInterface::class);
		$firstResolver->method('themeTemplateMaps')->willReturn([
			['blog' => TemplatePath::fromString('/theme-a/templates/blog')],
		]);

		$secondResolver = self::createStub(ThemeResolverInterface::class);
		$secondResolver->method('themeTemplateMaps')->willReturn([
			['blog' => TemplatePath::fromString('/theme-b/templates/blog')],
		]);

		new TemplateMapOverrides(['blog' => '/app/templates/blog'], $firstResolver, $pool)->forExtension('blog');
		new TemplateMapOverrides(['blog' => '/app/templates/blog'], $secondResolver, $pool)->forExtension('blog');

		self::assertCount(2, $keys);
		self::assertNotSame($keys[0], $keys[1]);
	}

	/**
	 * Test that an empty candidate list is returned and cached when there are no overrides.
	 */
	#[Test]
	public function testForExtensionReturnsEmptyWhenNoOverrides(): void
	{
		/** @var CacheItemInterface&MockObject $item */
		$item = $this->createMock(CacheItemInterface::class);
		$item->method('isHit')->willReturn(false);

		$pool = $this->createMock(CacheItemPoolInterface::class);
		$pool->method('getItem')->willReturn($item);

		$resolver = self::createStub(ThemeResolverInterface::class);
		$resolver->method('themeTemplateMaps')->willReturn([]);

		$item->expects(self::once())->method('set')->with([]);
		$pool->expects(self::once())->method('save')->with($item);

		$overrides = new TemplateMapOverrides([], $resolver, $pool);

		self::assertSame([], $overrides->forExtension('blog'));
	}

	/**
	 * Test that theme map entries are used when the extension is absent from the app overrides.
	 */
	#[Test]
	public function testForExtensionFallsBackToThemeMapsOnly(): void
	{
		/** @var CacheItemInterface&MockObject $item */
		$item = $this->createMock(CacheItemInterface::class);
		$item->method('isHit')->willReturn(false);

		$pool = self::createStub(CacheItemPoolInterface::class);
		$pool->method('getItem')->willReturn($item);

		$resolver = self::createStub(ThemeResolverInterface::class);
		$resolver->method('themeTemplateMaps')->willReturn([
			['blog' => TemplatePath::fromString('/theme/templates/blog')],
			['blog' => TemplatePath::fromString('/active/templates/blog')],
		]);

		$expected = [
			TemplatePath::fromString('/theme/templates/blog'),
			TemplatePath::fromString('/active/templates/blog'),
		];

		$item->expects(self::once())->method('set')->with($expected);

		$overrides = new TemplateMapOverrides(['shop' => '/app/templates/shop'], $resolver, $pool);

		$result = $overrides->forExtension('blog');

		self::assertCount(2, $result);
		self::assertSame('/theme/templates/blog', $result[0]->toNative());
		self::assertSame('/active/templates/blog', $result[1]->toNative());
	}
}
