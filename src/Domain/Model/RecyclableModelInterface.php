<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model;

use DateTimeInterface;

/**
 * Undocumented interface
 */
interface RecyclableModelInterface
{
    /**
     * Check weather in trash and returns true, otherwise false.
     *
     * @return boolean
     */
    public function inRecycle(): bool;

    /**
     * Get the recycled time.
     *
     * @return DateTimeInterface
     */
    public function getRecycledAt(): DateTimeInterface;
}
