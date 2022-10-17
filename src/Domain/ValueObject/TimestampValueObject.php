<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeInterface;

/**
 * TimestampValueObject class will be used for auditing purposes. It will help to holds created and updated datetime.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
final class TimestampValueObject
{
	// /**
	//  * Undocumented variable
	//  *
	//  * @var DateTimeInterface
	//  */
	// public readonly DateTimeInterface $createdAt;

	// /**
	//  * Undocumented variable
	//  *
	//  * @var DateTimeInterface
	//  */
	// public readonly DateTimeInterface $updatedAt;

	public function __construct(
		public readonly DateTimeInterface $createdAt,
		public readonly DateTimeInterface $updatedAt = $this->createdAt
	) {
		// $this->createdAt = $createdAt;
		// $this->updatedAt = $updatedAt ?? $this->createdAt;
	}
}
