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

namespace Webify\Base\Test\Domain\ValueObject;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\Exception\InvalidDatetimeException;
use Webify\Base\Domain\ValueObject\DateTimeValueObject;

/**
 * Date time value object test class.
 *
 * @coversDefaultClass  \Webify\Base\Domain\ValueObject\DateTimeValueObject
 *
 * @internal
 */
final class DateTimeValueObjectTest extends TestCase
{
	private const DATETIME = '2023-01-01 00:00:00';

	/**
	 * @covers ::create
	 */
	public function testCanBeCreatedWithoutArgument(): void
	{
		$this->assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::create()
		);
	}

	/**
	 * @covers ::create
	 */
	public function testCanBeCreatedWithValidDatetimeStringOrObject(): void
	{
		$datetimeObj = new DateTimeImmutable();

		$this->assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::create($datetimeObj)
		);
		$this->assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::create(self::DATETIME)
		);
	}

	/**
	 * @covers ::createFromFormat
	 */
	public function testCanBeCreatedWithGivenFormat(): void
	{
		$this->assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::createFromFormat('Y-m-d H:i:s', self::DATETIME)
		);
	}

	/**
	 * @covers ::create
	 */
	public function testCannotBeCreatedFromInvalidDatetimeString(): void
	{
		$this->expectException(InvalidDatetimeException::class);

		DateTimeValueObject::create('invalid_datetime');
	}

	/**
	 * @covers ::createFromFormat
	 */
	public function testCannotBeCreatedIfDatetimeStringAndFormatDiffers(): void
	{
		$this->expectException(InvalidDatetimeException::class);

		DateTimeValueObject::createFromFormat('Y-m-d', self::DATETIME);
	}

	/**
	 * @covers ::__toString
	 * @covers ::createFromFormat
	 */
	public function testEnsureItReturnsExpectedFormat(): void
	{
		$datetime = DateTimeValueObject::createFromFormat('Y-m-d H:i:s', self::DATETIME);

		$this->assertSame(
			self::DATETIME,
			(string) $datetime
		);
	}
}
