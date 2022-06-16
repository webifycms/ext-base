<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeInterface;

/**
 * RecycleValueObject class
 */
final class RecycleValueObject
{
    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $recycledAt;

    /**
     * Recycle value object constructor.
     */
    public function __construct(DateTimeInterface $recycledAt)
    {
        $this->recycledAt = $recycledAt;
    }

    /**
     * Get the value of recycledAt
     */
    public function getRecycledAt(): DateTimeInterface
    {
        return $this->recycledAt;
    }
}
