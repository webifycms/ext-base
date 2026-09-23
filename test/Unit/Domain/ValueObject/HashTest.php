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

namespace Webify\Base\Test\Unit\Domain\ValueObject;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\Exception\InvalidHashException;
use Webify\Base\Domain\ValueObject\Hash;

/**
 * HashTest tests the functionality of the Hash value object.
 *
 * @internal
 */
#[CoversClass(Hash::class)]
#[CoversMethod(Hash::class, 'generate')]
#[CoversMethod(Hash::class, 'fromString')]
#[CoversMethod(Hash::class, 'toNative')]
#[CoversMethod(Hash::class, 'equals')]
final class HashTest extends TestCase
{
	/**
	 * Test that generate produces a non-empty string.
	 */
	#[Test]
	public function testGenerateReturnsNonEmptyString(): void
	{
		$hash = Hash::generate('crc32', 'ext/blog@1.0.0');

		self::assertNotEmpty($hash->toNative());
	}

	/**
	 * Test that generate is deterministic: same inputs produce the same output.
	 */
	#[Test]
	public function testGenerateIsDeterministic(): void
	{
		$hash1 = Hash::generate('crc32', 'ext/blog@1.0.0');
		$hash2 = Hash::generate('crc32', 'ext/blog@1.0.0');

		self::assertTrue($hash1->equals($hash2));
		self::assertSame($hash1->toNative(), $hash2->toNative());
	}

	/**
	 * Test that generate uses the default length of 6 characters.
	 */
	#[Test]
	public function testGenerateUsesDefaultLength(): void
	{
		$hash = Hash::generate('crc32', 'ext/blog@1.0.0');

		self::assertSame(6, strlen($hash->toNative()));
	}

	/**
	 * Test that generate honors a custom length.
	 */
	#[Test]
	public function testGenerateHonorsCustomLength(): void
	{
		$hash = Hash::generate('sha256', 'ext/blog@1.0.0', 12);

		self::assertSame(12, strlen($hash->toNative()));
	}

	/**
	 * Test that generate returns an instance of the Hash value object.
	 */
	#[Test]
	public function testGenerateReturnsHashInstance(): void
	{
		$hash = Hash::generate('crc32', 'ext/blog@1.0.0');

		self::assertInstanceOf(Hash::class, $hash);
	}

	/**
	 * Test that different algorithms produce different hashes.
	 */
	#[Test]
	public function testDifferentAlgorithmsProduceDifferentHashes(): void
	{
		$hash1 = Hash::generate('crc32', 'ext/blog@1.0.0');
		$hash2 = Hash::generate('sha256', 'ext/blog@1.0.0');

		self::assertNotSame($hash1->toNative(), $hash2->toNative());
	}

	/**
	 * Test that different data produce different hashes.
	 */
	#[Test]
	public function testDifferentDataProduceDifferentHashes(): void
	{
		$hash1 = Hash::generate('sha256', 'ext/blog@1.0.0');
		$hash2 = Hash::generate('sha256', 'ext/blog@1.0.1');

		self::assertNotSame($hash1->toNative(), $hash2->toNative());
	}

	/**
	 * Test that fromString creates an instance from a valid string.
	 */
	#[Test]
	public function testFromStringWithValidString(): void
	{
		$hash = Hash::fromString('abc123');

		self::assertSame('abc123', $hash->toNative());
	}

	/**
	 * Test that __toString delegates to toNative.
	 */
	#[Test]
	public function testToStringDelegatesToToNative(): void
	{
		$hash = Hash::fromString('k7x9m2');

		self::assertSame('k7x9m2', (string) $hash);
	}

	/**
	 * Test that equals returns true for identical values.
	 */
	#[Test]
	public function testEqualsReturnsTrueForIdenticalValues(): void
	{
		$hash1 = Hash::fromString('abc123');
		$hash2 = Hash::fromString('abc123');

		self::assertTrue($hash1->equals($hash2));
	}

	/**
	 * Test that equals returns false for different values.
	 */
	#[Test]
	public function testEqualsReturnsFalseForDifferentValues(): void
	{
		$hash1 = Hash::fromString('abc123');
		$hash2 = Hash::fromString('xyz789');

		self::assertFalse($hash1->equals($hash2));
	}

	/**
	 * Test that the generated hash is always lowercase.
	 */
	#[Test]
	public function testGenerateProducesLowercaseHash(): void
	{
		$hash = Hash::generate('sha256', 'theme/starter@2.1.0');

		self::assertSame($hash->toNative(), strtolower($hash->toNative()));
	}

	/**
	 * Test that fromString throws exception for empty string.
	 */
	#[Test]
	public function testFromStringThrowsExceptionForEmptyString(): void
	{
		$this->expectException(InvalidHashException::class);

		Hash::fromString('');
	}
}
