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

namespace Webify\Base\Test\Unit\Domain\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\ValueObject\Slug;
use Webify\Base\Test\Unit\Domain\ValueObject\Example\ExampleSlug;

/**
 * SlugTest tests the functionality of the Slug base value object.
 *
 * @internal
 */
#[CoversClass(Slug::class)]
#[CoversMethod(Slug::class, 'fromString')]
#[CoversMethod(Slug::class, 'toNative')]
#[CoversMethod(Slug::class, 'equals')]
#[CoversMethod(Slug::class, '__toString')]
final class SlugTest extends TestCase
{
	/**
	 * Tests creating a valid Slug from string.
	 */
	#[Test]
	public function testCreateValidSlugFromString(): void
	{
		$slug = ExampleSlug::fromString('test-slug');

		self::assertInstanceOf(Slug::class, $slug);
		self::assertSame('test-slug', $slug->toNative());
	}

	/**
	 * Tests that an exception is thrown when creating Slug with uppercase letters.
	 */
	#[Test]
	public function testUppercaseSlugThrowsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		ExampleSlug::fromString('Test-Slug');
	}

	/**
	 * Tests that an exception is thrown when creating Slug starting with hyphen.
	 */
	#[Test]
	public function testSlugStartingWithHyphenThrowsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		ExampleSlug::fromString('-test');
	}

	/**
	 * Tests that an exception is thrown when creating Slug ending with hyphen.
	 */
	#[Test]
	public function testSlugEndingWithHyphenThrowsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		ExampleSlug::fromString('test-');
	}

	/**
	 * Tests that an exception is thrown when creating Slug with consecutive hyphens.
	 */
	#[Test]
	public function testSlugWithConsecutiveHyphensThrowsException(): void
	{
		$this->expectException(InvalidArgumentException::class);
		ExampleSlug::fromString('test--slug');
	}

	/**
	 * Tests equals method returns true for same values.
	 */
	#[Test]
	public function testEqualsReturnsTrueForSameValues(): void
	{
		$slug1 = ExampleSlug::fromString('admin');
		$slug2 = ExampleSlug::fromString('admin');

		self::assertTrue($slug1->equals($slug2));
	}

	/**
	 * Tests equals method returns false for different values.
	 */
	#[Test]
	public function testEqualsReturnsFalseForDifferentValues(): void
	{
		$slug1 = ExampleSlug::fromString('admin');
		$slug2 = ExampleSlug::fromString('editor');

		self::assertFalse($slug1->equals($slug2));
	}

	/**
	 * Tests string representation.
	 */
	#[Test]
	public function testToStringReturnsStringValue(): void
	{
		$slug = ExampleSlug::fromString('test-slug');

		self::assertSame('test-slug', (string) $slug);
	}
}
