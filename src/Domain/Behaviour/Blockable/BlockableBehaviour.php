<?php

namespace OneCMS\Base\Domain\Behaviour\Blockable;

use DateTimeInterface;

/**
 * Trait BlockableBehaviour
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
trait BlockableBehaviour
{
    /**
     * @var ?DateTimeInterface
     */
    private ?DateTimeInterface $blockedAt = null;

    /**
     * @return ?DateTimeInterface
     */
    public function getBlockedAt(): ?DateTimeInterface
    {
        return $this->blockedAt;
    }

    /**
     * @param ?DateTimeInterface $blockedAt
     */
    public function setBlockedAt(?DateTimeInterface $blockedAt): void
    {
        $this->blockedAt = $blockedAt;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blockedAt instanceof DateTimeInterface;
    }
}