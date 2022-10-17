<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeInterface;

/**
 * BlockableValueObject class.
 */
final class BlockableValueObject
{
	/**
	 * Blockable value object constructor.
	 */
	public function __construct(
		private readonly DateTimeInterface $blockedAt = (new DateTimeValueObject())->getDateTime()
	) {
	}

	/**
	 * Returns blocked at datetime.
	 */
	public function getBlockedAt(): DateTimeInterface
	{
		return $this->blockedAt;
	}
}
