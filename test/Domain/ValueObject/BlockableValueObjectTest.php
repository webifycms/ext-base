<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\ValueObject;

use DateTimeImmutable;
use OneCMS\Base\Domain\ValueObject\BlockableValueObject;
use PHPUnit\Framework\TestCase;

/**
 * BlockableValueObjectTest class
 */
final class BlockableValueObjectTest extends TestCase
{
    public function testCanBeCreatedWithoutDatetime(): void
    {
        $this->assertInstanceOf(
            BlockableValueObject::class,
            new BlockableValueObject()
        );
    }
    
    public function testCanBeCreatedWithValidDatetime(): void
    {
        $datetime = new DateTimeImmutable();
        $blockableObject = new BlockableValueObject($datetime);

        $this->assertInstanceOf(
            BlockableValueObject::class,
            $blockableObject
        );
        $this->assertEquals($datetime, $blockableObject->getBlockedAt());
    }
}
