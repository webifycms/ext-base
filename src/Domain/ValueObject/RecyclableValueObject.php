<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeInterface;

/**
 * Undocumented class
 */
final class RecyclableValueObject
{
    /**
     * @var ?DateTimeInterface
     */
    private ?DateTimeInterface $trashedAt = null;

    /**
     * Blockable value object constructor.
     */
    public function __construct(?DateTimeInterface $trashedAt = null)
    {
        if (!is_null($trashedAt)) {
            $this->trashedAt = $trashedAt;
        }
    }

    /**
     * Get the value of trashedAt
     */
    public function getTrashedAt(): ?DateTimeInterface
    {
        return $this->trashedAt;
    }

    public function inTrashed(): bool
    {
        return $this->trashedAt instanceof DateTimeInterface;
    }
}
