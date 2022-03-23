<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model\ValueObject;

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
     *
     * @param DateTimeInterface|null $blockedAt
     */
    public function __construct(?DateTimeInterface $blockedAt = null)
    {
        if (!is_null($blockedAt)) {
            $this->blockedAt = $blockedAt;
        }
    }

    /**
     * Get the value of value
     *
     * @return DateTimeInterface|null
     */
    public function getBlockedAt(): ?DateTimeInterface
    {
        return $this->blockedAt;
    }

    /**
     * Check is it blocked
     * 
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blockedAt instanceof DateTimeInterface;
    }
}
