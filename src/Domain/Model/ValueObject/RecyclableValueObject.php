<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Model\ValueObject;

use DateTimeInterface;

/**
 * Undocumented class
 */
final class RecyclableValueObject
{
    /**
     * @var DateTimeInterface|null
     */
    private ?DateTimeInterface $trashedAt = null;

    /**
     * Blockable value object constructor.
     *
     * @param DateTimeInterface|null $trashedAt
     */
    public function __construct(?DateTimeInterface $trashedAt = null)
    {
        if (!is_null($trashedAt)) {
            $this->trashedAt = $trashedAt;
        }
    }

    /**
     * Get the value of trashedAt
     *
     * @return DateTimeInterface|null
     */
    public function getTrashedAt(): ?DateTimeInterface
    {
        return $this->trashedAt;
    }

    /**
     * @return bool
     */
    public function isTrashed(): bool
    {
        return $this->trashedAt instanceof DateTimeInterface;
    }
}
