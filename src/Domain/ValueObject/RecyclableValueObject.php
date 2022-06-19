<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeInterface;

/**
 * RecyclableValueObject class
 */
final class RecyclableValueObject
{
    /**
     * Recycle value object constructor.
     */
    public function __construct(
        private readonly DateTimeInterface $recycledAt = (new DateTimeValueObject())->getDateTime()
    ) {
    }

    /**
     * Get the value of recycledAt
     */
    public function getRecycledAt(): DateTimeInterface
    {
        return $this->recycledAt;
    }
}
