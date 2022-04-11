<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use OneCMS\Base\Domain\Service\Uuid\UuidServiceInterface;

/**
 * UuidValueObject class for Uuid - Universal Unique Identity
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class UuidValueObject
{
    /**
     * @var string
     */
    private readonly string $uuid;

    /**
     * Uuid constructor.
     */
    public function __construct(UuidServiceInterface $uuidService)
    {
        $this->uuid = $uuidService->uuid()->toString();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
