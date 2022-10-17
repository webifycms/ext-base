<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\ValueObject;

use DateTimeImmutable;
use OneCMS\Base\Domain\Exception\InvalidDatetimeException;
use OneCMS\Base\Domain\ValueObject\DateTimeValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DateTimeValueObjectTest extends TestCase
{
	public const DATETIME = '2022-01-01 00:00:00';

	private DateTimeValueObject $datetime;

	protected function setUp(): void
	{
		$this->datetime = new DateTimeValueObject(self::DATETIME);
	}

	public function testCanCreateFromValidDatetimeType(): void
	{
		static::assertInstanceOf(
			DateTimeValueObject::class,
			$this->datetime
		);
	}

	public function testCannotCreateFromInvalidDatetimeType(): DateTimeValueObject
	{
		$this->expectException(InvalidDatetimeException::class);

		return new DateTimeValueObject('invalid_datetime');
	}

	public function testDatetimeReturnsDefaultW3CFormatIfFormatNotGiven(): void
	{
		$datetime = new DateTimeImmutable(self::DATETIME);

		static::assertSame(
			$datetime->format(DateTimeValueObject::DEFAULT_FORMAT),
			$this->datetime->getFormattedValue()
		);
	}

	public function testEnsureDatetimeReturnsExpectedFormat(): void
	{
		$datetime = new DateTimeImmutable(self::DATETIME);
		$format   = 'Y-m-d';

		static::assertSame(
			$datetime->format($format),
			$this->datetime->getFormattedValue($format)
		);
	}

	public function testEnsureDatetimeGivesTheDefaultTimezone(): void
	{
		$timezone = 'Asia/Colombo';

		date_default_timezone_set($timezone);

		static::assertSame(
			$timezone,
			(new DateTimeValueObject())->getDateTime()->getTimezone()->getName()
		);
	}
}
