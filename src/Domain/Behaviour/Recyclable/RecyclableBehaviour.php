<?php

namespace OneCMS\Base\Domain\Behaviour\Recyclable;

use DateTimeInterface;

/**
 * Trait RecyclableBehaviour
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
trait RecyclableBehaviour
{
    /**
     * @var ?DateTimeInterface
     */
    private ?DateTimeInterface $trashedAt = null;

    /**
     * @return ?DateTimeInterface
     */
    public function getTrashedAt(): ?DateTimeInterface
    {
        return $this->trashedAt;
    }

    /**
     * @param ?DateTimeInterface $trashedAt
     */
    public function setTrashedAt(?DateTimeInterface $trashedAt): void
    {
        $this->trashedAt = $trashedAt;
    }

    /**
     * @return bool
     */
    public function inTrashed(): bool
    {
        return $this->trashedAt instanceof DateTimeInterface;
    }
}