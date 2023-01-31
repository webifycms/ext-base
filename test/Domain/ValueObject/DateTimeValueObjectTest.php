<?php
/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\ValueObject;

use OneCMS\Base\Domain\Exception\InvalidDatetimeException;
use OneCMS\Base\Domain\ValueObject\DateTimeValueObject;
use PHPUnit\Framework\TestCase;

/**
 * Date time value object test class.
 */
final class DateTimeValueObjectTest extends TestCase
{
	private const DATETIME = '2023-01-01 00:00:00';

	/**
	 * @covers \DateTimeValueObject::create
	 */
	public static function testCanBeCreatedWithoutArgument(): void
	{
		static::assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::create()
		);
	}

	/**
	 * @covers \DateTimeValueObject::create
	 */
	public static function testCanBeCreatedWithValidDatetimeStringOrObject(): void
	{
		$datetimeObj = new \DateTimeImmutable();

		static::assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::create($datetimeObj)
		);
		static::assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::create(self::DATETIME)
		);
	}

	/**
	 * @covers \DateTimeValueObject::createFromFormat
	 */
	public function testCanBeCreatedWithGivenFormat(): void
	{
		static::assertInstanceOf(
			DateTimeValueObject::class,
			DateTimeValueObject::createFromFormat('Y-m-d H:i:s', self::DATETIME)
		);
	}

	/**
	 * @covers \DateTimeValueObject::create
	 */
	public function testCannotBeCreatedFromInvalidDatetimeString(): void
	{
		$this->expectException(InvalidDatetimeException::class);

		DateTimeValueObject::create('invalid_datetime');
	}

	/**
	 * @covers \DateTimeValueObject::createFromFormat
	 */
	public function testCannotBeCreatedIfDatetimeStringAndFormatDiffers(): void
	{
		$this->expectException(InvalidDatetimeException::class);

		DateTimeValueObject::createFromFormat('Y-m-d', self::DATETIME);
	}

	/**
	 * @covers \DateTimeValueObject::__toString
	 * @covers \DateTimeValueObject::createFromFormat
	 */
	public function testEnsureItReturnsExpectedFormat(): void
	{
		$datetime = DateTimeValueObject::createFromFormat('Y-m-d H:i:s', self::DATETIME);

		static::assertSame(
			self::DATETIME,
			(string) $datetime
		);
	}
}
