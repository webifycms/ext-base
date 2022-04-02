<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\Model\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use OneCMS\Base\Domain\Model\ValueObject\BlockableValueObject;
use PHPUnit\Framework\TestCase;

/**
 * BlockableValueObjectTest class
 */
final class BlockableValueObjectTest extends TestCase
{
    private BlockableValueObject $blockedObject;
    private BlockableValueObject $unblockedObject;

    public function setUp(): void
    {
        $this->blockedObject = new BlockableValueObject(new DateTimeImmutable());
        $this->unblockedObject = new BlockableValueObject();
    }

    public function testBlockedObjectIsBlocked(): bool
    {
        $this->assertTrue($this->blockedObject->isBlocked());

        return $this->blockedObject->isBlocked();
    }

    public function testBlockedObjectBlockedAtReturnsDatetime(): DateTimeInterface
    {
        $this->assertInstanceOf(DateTimeInterface::class, $this->blockedObject->getBlockedAt());

        return $this->blockedObject->getBlockedAt();
    }

    public function testUnblcokedObjectIsNotBlocked(): bool
    {
        $this->assertFalse($this->unblockedObject->isBlocked());

        return $this->unblockedObject->isBlocked();
    }

    public function testUnblockedObjectBlockedAtReturnsNull(): void
    {
        $this->assertNull($this->unblockedObject->getBlockedAt());

        $this->unblockedObject->getBlockedAt();
    }
}
