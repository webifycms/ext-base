<?php

declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\Model\ValueObject;

use OneCMS\Base\Domain\Model\ValueObject\UuidValueObject;
use OneCMS\Base\Domain\Service\Uuid\UuidServiceInterface;
use OneCMS\Base\Infrastructure\Service\Uuid\UuidService;
use PHPUnit\Framework\TestCase;

final class UuidValueObjectTest extends TestCase
{
    private UuidServiceInterface $uuidService;

    public function setUp(): void
    {
        $this->uuidService = $this->createMock(UuidServiceInterface::class);
    }

    public function testCanCreateFromUuidService(): void
    {
        $this->assertInstanceOf(
            UuidValueObject::class,
            new UuidValueObject($this->uuidService)
        );
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertIsString((new UuidValueObject($this->uuidService))->getUuid());
    }
}
