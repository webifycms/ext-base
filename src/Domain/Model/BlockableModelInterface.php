<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model;

use DateTimeInterface;

/**
 * Undocumented interface.
 */
interface BlockableModelInterface
{
	/**
	 * If it is blocked returns true, otherwise false.
	 */
	public function isBlocked(): bool;

	/**
	 * Returns blocked at datetime.
	 */
	public function getBlockedAt(): DateTimeInterface;
}
