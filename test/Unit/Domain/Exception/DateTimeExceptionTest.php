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

namespace Webify\Base\Test\Unit\Domain\Exception;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Webify\Base\Domain\Contract\Translation\ExceptionTranslation;
use Webify\Base\Domain\Exception\DateTimeException;

/**
 * DateTimeExceptionTest tests the functionality of the DateTimeException class.
 *
 * @internal
 */
#[CoversClass(DateTimeException::class)]
#[CoversMethod(DateTimeException::class, 'forDefault')]
#[CoversMethod(DateTimeException::class, 'forInvalidDatetime')]
#[CoversMethod(DateTimeException::class, 'forInvalidTimezone')]
#[UsesClass(ExceptionTranslation::class)]
final class DateTimeExceptionTest extends TestCase
{
	/**
	 * Test the forDefault factory method creates an exception with the default message.
	 */
	#[Test]
	public function testForDefault(): void
	{
		$exception = DateTimeException::forDefault();

		self::assertInstanceOf(DateTimeException::class, $exception);
		self::assertSame('Failed to create DateTime object with current date and time.', $exception->getMessage());
		self::assertSame('base.datetime', $exception->translation->group);
		self::assertSame('default', $exception->translation->key);
	}

	/**
	 * Test the forInvalidDatetime factory method with an invalid datetime string.
	 */
	#[Test]
	public function testForInvalidDatetime(): void
	{
		$exception = DateTimeException::forInvalidDatetime('not-a-date');

		self::assertInstanceOf(DateTimeException::class, $exception);
		self::assertSame(
			'Failed to create DateTime object due to invalid datetime "not-a-date"',
			$exception->getMessage()
		);
		self::assertSame('base.datetime', $exception->translation->group);
		self::assertSame('invalid_datetime', $exception->translation->key);
		self::assertSame(['value' => 'not-a-date'], $exception->translation->params);
	}

	/**
	 * Test the forInvalidTimezone factory method with an invalid timezone string.
	 */
	#[Test]
	public function testForInvalidTimezone(): void
	{
		$exception = DateTimeException::forInvalidTimezone('Invalid/Timezone');

		self::assertInstanceOf(DateTimeException::class, $exception);
		self::assertSame(
			'Failed to create DateTime object due to invalid timezone "Invalid/Timezone"',
			$exception->getMessage()
		);
		self::assertSame('base.datetime', $exception->translation->group);
		self::assertSame('invalid_timezone', $exception->translation->key);
		self::assertSame(['value' => 'Invalid/Timezone'], $exception->translation->params);
	}

	/**
	 * Test the forDefault factory method with a previous exception.
	 */
	#[Test]
	public function testForDefaultWithPreviousException(): void
	{
		$previous  = new RuntimeException('previous error');
		$exception = DateTimeException::forDefault(previous: $previous);

		self::assertSame($previous, $exception->getPrevious());
	}

	/**
	 * Test the forInvalidDatetime factory method with a custom exception code.
	 */
	#[Test]
	public function testForInvalidDatetimeWithCode(): void
	{
		$exception = DateTimeException::forInvalidDatetime('bad-date', 400);

		self::assertSame(400, $exception->getCode());
	}
}
