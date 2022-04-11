<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeInterface;

/**
 * BlockableValueObject class
 */
final class BlockableValueObject
{
    /**
     * @var DateTimeInterface|null
     */
    private ?DateTimeInterface $blockedAt = null;

    /**
     * Blockable value object constructor.
     */
    public function __construct(?DateTimeInterface $blockedAt = null)
    {
        if (!is_null($blockedAt)) {
            $this->blockedAt = $blockedAt;
        }
    }

    /**
     * Get the value of value
     */
    public function getBlockedAt(): ?DateTimeInterface
    {
        return $this->blockedAt;
    }

    /**
     * Check is it blocked
     */
    public function isBlocked(): bool
    {
        return $this->blockedAt instanceof DateTimeInterface;
    }
}
