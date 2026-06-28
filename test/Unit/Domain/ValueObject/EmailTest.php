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

namespace Webify\Base\Test\Unit\Domain\ValueObject;

use Faker\{Factory, Generator};
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\ValueObject\Email;
use Webify\Base\Test\Unit\Domain\ValueObject\Example\ExampleEmail;

/**
 * EmailTest tests the functionality of the Email class.
 *
 * @internal
 */
#[CoversClass(Email::class)]
#[CoversMethod(Email::class, 'fromString')]
#[CoversMethod(Email::class, 'toNative')]
final class EmailTest extends TestCase
{
	/**
	 * Faker generator for creating realistic test data.
	 */
	private Generator $faker;

	/**
	 * Set up the test fixture.
	 */
	protected function setUp(): void
	{
		$this->faker = Factory::create();
	}

	/**
	 * Test creating an email instance from a valid email string.
	 */
	#[Test]
	public function testFromStringCreatesInstance(): void
	{
		$email = ExampleEmail::fromString($this->faker->email());

		self::assertInstanceOf(ExampleEmail::class, $email);
	}

	/**
	 * Test that email addresses are converted to lowercase.
	 */
	#[Test]
	public function testFromStringConvertsToLowercase(): void
	{
		$email = ExampleEmail::fromString('TEST@EXAMPLE.COM');

		self::assertSame('test@example.com', $email->toNative());
	}

	/**
	 * Test that surrounding whitespace is trimmed from email addresses.
	 */
	#[Test]
	public function testFromStringTrimsWhitespace(): void
	{
		$email = ExampleEmail::fromString('  test@example.com  ');

		self::assertSame('test@example.com', $email->toNative());
	}

	/**
	 * Test that toNative returns the original email string.
	 */
	#[Test]
	public function testToNativeReturnsEmailString(): void
	{
		$email = ExampleEmail::fromString($emailString = $this->faker->email());

		self::assertSame($emailString, $email->toNative());
	}

	/**
	 * Test string conversion via the __toString magic method.
	 */
	#[Test]
	public function testToStringMagicMethod(): void
	{
		$email = ExampleEmail::fromString($emailString = $this->faker->email());

		self::assertSame($emailString, (string) $email);
	}

	/**
	 * Test valid email with a common domain.
	 */
	#[Test]
	public function testValidEmailWithCommonDomain(): void
	{
		$email = ExampleEmail::fromString($emailString = $this->faker->email());

		self::assertSame($emailString, $email->toNative());
	}

	/**
	 * Test valid email with a subdomain.
	 */
	#[Test]
	public function testValidEmailWithSubdomain(): void
	{
		$email = ExampleEmail::fromString('user@mail.example.com');

		self::assertSame('user@mail.example.com', $email->toNative());
	}

	/**
	 * Test valid email with plus addressing (sub-addressing).
	 */
	#[Test]
	public function testValidEmailWithPlusAddressing(): void
	{
		$email = ExampleEmail::fromString('user+tag@example.com');

		self::assertSame('user+tag@example.com', $email->toNative());
	}

	/**
	 * Test valid email with a dot in the local part.
	 */
	#[Test]
	public function testValidEmailWithDotInLocalPart(): void
	{
		$email = ExampleEmail::fromString('first.last@example.com');

		self::assertSame('first.last@example.com', $email->toNative());
	}

	/**
	 * Test that an empty string throws an exception.
	 */
	#[Test]
	public function testFromStringThrowsForEmptyValue(): void
	{
		$this->expectException(InvalidArgumentException::class);

		ExampleEmail::fromString('');
	}

	/**
	 * Test that a whitespace-only string throws an exception.
	 */
	#[Test]
	public function testFromStringThrowsForWhitespaceOnly(): void
	{
		$this->expectException(InvalidArgumentException::class);

		ExampleEmail::fromString('   ');
	}

	/**
	 * Test that an invalid email format throws an exception.
	 */
	#[Test]
	public function testFromStringThrowsForInvalidEmailFormat(): void
	{
		$this->expectException(InvalidArgumentException::class);

		ExampleEmail::fromString('not-an-email');
	}

	/**
	 * Test that an email missing the @ symbol throws an exception.
	 */
	#[Test]
	public function testFromStringThrowsForMissingAtSymbol(): void
	{
		$this->expectException(InvalidArgumentException::class);

		ExampleEmail::fromString('example.com');
	}

	/**
	 * Test that an email missing the local part throws an exception.
	 */
	#[Test]
	public function testFromStringThrowsForMissingLocalPart(): void
	{
		$this->expectException(InvalidArgumentException::class);

		ExampleEmail::fromString('@example.com');
	}

	/**
	 * Test that an email missing the domain throws an exception.
	 */
	#[Test]
	public function testFromStringThrowsForMissingDomain(): void
	{
		$this->expectException(InvalidArgumentException::class);

		ExampleEmail::fromString('test@');
	}

	/**
	 * Test that an email exceeding the maximum length throws an exception.
	 */
	#[Test]
	public function testFromStringThrowsForEmailExceedingMaxLength(): void
	{
		$this->expectException(InvalidArgumentException::class);

		$longLocalPart = str_repeat('a', 250);
		ExampleEmail::fromString($longLocalPart . '@example.com');
	}

	/**
	 * Test that tempmail.com is blocked.
	 */
	#[Test]
	public function testBlockedDomainTempmail(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Disposable email addresses are not allowed.');

		ExampleEmail::fromString('test@tempmail.com');
	}

	/**
	 * Test that mailinator.com is blocked.
	 */
	#[Test]
	public function testBlockedDomainMailinator(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Disposable email addresses are not allowed.');

		ExampleEmail::fromString('test@mailinator.com');
	}

	/**
	 * Test that guerrillamail.com is blocked.
	 */
	#[Test]
	public function testBlockedDomainGuerrillamail(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Disposable email addresses are not allowed.');

		ExampleEmail::fromString('test@guerrillamail.com');
	}

	/**
	 * Test that two email instances with the same value are considered equal.
	 */
	#[Test]
	public function testEmailValueObjectEquality(): void
	{
		$emailString = $this->faker->email();
		$email1      = ExampleEmail::fromString($emailString);
		$email2      = ExampleEmail::fromString($emailString);

		self::assertSame($email1->toNative(), $email2->toNative());
	}
}
