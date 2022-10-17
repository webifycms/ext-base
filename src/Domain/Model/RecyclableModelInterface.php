<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model;

use DateTimeInterface;

/**
 * Undocumented interface.
 */
interface RecyclableModelInterface
{
	/**
	 * Check weather in trash and returns true, otherwise false.
	 */
	public function inRecycle(): bool;

	/**
	 * Returns the recycled datetime.
	 */
	public function getRecycledAt(): DateTimeInterface;
}
