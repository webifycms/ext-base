<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model;

use DateTimeInterface;

/**
 * Undocumented interface
 */
interface BlockableModelInterface
{
    public function isBlocked(): bool;

    public function getBlockedAt(): DateTimeInterface;
}
