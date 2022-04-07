<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\Model\ValueObject;

use DateTimeInterface;
use OneCMS\Base\Domain\Model\ValueObject\DateTimeValueObject;
use OneCMS\Base\Domain\Model\ValueObject\RecyclableValueObject;
use PHPUnit\Framework\TestCase;

final class RecyclableValueObjectTest extends TestCase
{
    private RecyclableValueObject $trashedObject;

    private RecyclableValueObject $normalObject;

    public function setUp(): void
    {
        $this->trashedObject = new RecyclableValueObject((new DateTimeValueObject())->getDateTime());
        $this->normalObject = new RecyclableValueObject();
    }

    public function testEnsureTheTrashedObjectIsInTrash(): void
    {
        $this->assertTrue($this->trashedObject->inTrashed());
    }

    public function testTrashedObjectShouldProvideDatetimeOfTrash(): void
    {
        $this->assertInstanceOf(
            DateTimeInterface::class,
            $this->trashedObject->getTrashedAt()
        );
    }

    public function testEnsureTheNormalObjectIsNotInTrash(): void
    {
        $this->assertFalse($this->normalObject->inTrashed());
    }

    public function testNormalObjectShouldProvideNullForTrashedAt(): void
    {
        $this->assertNull($this->normalObject->getTrashedAt());
    }
}
