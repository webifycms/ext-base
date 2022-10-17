<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\ValueObject;

use DateTimeImmutable;
use OneCMS\Base\Domain\ValueObject\BlockableValueObject;
use PHPUnit\Framework\TestCase;

/**
 * BlockableValueObjectTest class.
 *
 * @internal
 *
 * @coversNothing
 */
final class BlockableValueObjectTest extends TestCase
{
	public function testCanBeCreatedWithoutDatetime(): void
	{
		static::assertInstanceOf(
			BlockableValueObject::class,
			new BlockableValueObject()
		);
	}

	public function testCanBeCreatedWithValidDatetime(): void
	{
		$datetime        = new DateTimeImmutable();
		$blockableObject = new BlockableValueObject($datetime);

		static::assertInstanceOf(
			BlockableValueObject::class,
			$blockableObject
		);
		static::assertSame($datetime, $blockableObject->getBlockedAt());
	}
}
