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

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Webify\Base\Domain\Service\SlugifyInterface;
use Webify\Base\Infrastructure\Service\SymfonyAsciiSlugify;

/**
 * SymfonyAsciiSlugifyTest tests the functionality of the SymfonyAsciiSlugify class.
 *
 * @internal
 */
#[CoversClass(SymfonyAsciiSlugify::class)]
#[CoversMethod(SymfonyAsciiSlugify::class, 'slugify')]
final class SymfonyAsciiSlugifyTest extends TestCase
{
	/**
	 * Test that the slugify service implements the SlugifyInterface.
	 */
	#[Test]
	public function testImplementsSlugifyInterface(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertInstanceOf(SlugifyInterface::class, $slugify);
	}

	/**
	 * Test slugify with a simple text input.
	 */
	#[Test]
	public function testSlugifyWithSimpleText(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('Hello-World', $slugify->slugify('Hello World'));
	}

	/**
	 * Test slugify with special characters.
	 */
	#[Test]
	public function testSlugifyWithSpecialCharacters(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('Hello-World', $slugify->slugify('Hello, World!'));
	}

	/**
	 * Test slugify with accented characters.
	 */
	#[Test]
	public function testSlugifyWithAccentedCharacters(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('Creme-Brulee', $slugify->slugify('Crème Brûlée'));
	}

	/**
	 * Test slugify with multiple consecutive spaces.
	 */
	#[Test]
	public function testSlugifyWithMultipleSpaces(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('Hello-World', $slugify->slugify('Hello   World'));
	}

	/**
	 * Test slugify with leading and trailing spaces.
	 */
	#[Test]
	public function testSlugifyWithLeadingAndTrailingSpaces(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('Hello-World', $slugify->slugify('  Hello World  '));
	}

	/**
	 * Test slugify with uppercase text (slugify does not lowercase by default).
	 */
	#[Test]
	public function testSlugifyWithUppercaseText(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('HELLO-WORLD', $slugify->slugify('HELLO WORLD'));
	}

	/**
	 * Test slugify with numeric characters.
	 */
	#[Test]
	public function testSlugifyWithNumbers(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('Page-123', $slugify->slugify('Page 123'));
	}

	/**
	 * Test slugify with an empty string returns an empty string.
	 */
	#[Test]
	public function testSlugifyWithEmptyString(): void
	{
		$slugify = new SymfonyAsciiSlugify(new AsciiSlugger());

		self::assertSame('', $slugify->slugify(''));
	}
}
