<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\ValueObject;

use DateTimeInterface;
use OneCMS\Base\Domain\ValueObject\TimestampValueObject;
use PHPUnit\Framework\TestCase;

class TimestampValueObjectTest extends TestCase
{
    public function testCanBeCreatedWithoutProvidingTimestamp(): void
    {
        $this->assertInstanceOf(
            TimestampValueObject::class,
            new TimestampValueObject()
        );
    }

    public function testTimestampsShouldProvideDatetimeObjects(): void
    {
        $timestamp = new TimestampValueObject();

        $this->assertInstanceOf(
            DateTimeInterface::class,
            $timestamp->getCreatedAt()
        );

        $this->assertInstanceOf(
            DateTimeInterface::class,
            $timestamp->getUpdatedAt()
        );
    }
}
