<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use OneCMS\Base\Domain\ValueObject\TimestampValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class TimestampValueObjectTest extends TestCase
{
	public function testCanBeCreatedWithoutProvidingUpdatedAtTimestamp(): void
	{
		static::assertInstanceOf(
			TimestampValueObject::class,
			new TimestampValueObject(new DateTimeImmutable())
		);
	}

	public function testTimestampsShouldProvideDatetimeObjects(): void
	{
		$timestamp = new TimestampValueObject(new DateTimeImmutable());

		static::assertInstanceOf(
			DateTimeInterface::class,
			$timestamp->createdAt
		);

		static::assertInstanceOf(
			DateTimeInterface::class,
			$timestamp->updatedAt
		);
	}
}
