<?php

/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use OneCMS\Base\Domain\Exception\InvalidUniqueIdException;

/**
 * UniqueIdValueObject that helps to generate and validate an unique ID
 * that contains alphanumeric characters and length between 10 to 14 as default.
 */
class UniqueIdValueObject
{
	/**
	 * The characters which are used to generate an Unique ID.
	 */
	protected const CHARACTERS = [
		'bcdfghjklmnpqrstvwxyz',
		'0123456789',
		'BCDFGHJKLMNPQRSTVWXYZ',
	];

	/**
	 * Allowed characters length.
	 */
	protected const LENGTH = [
		'min' => 10,
		'max' => 14,
	];

	/**
	 * The object private constructor.
	 *
	 * @throws InvalidUniqueIdException
	 */
	final public function __construct(
		private readonly string $uniqueId
	) {
		if (!$this->isValid($this->uniqueId)) {
			throw new InvalidUniqueIdException('invalid_unique_id', ['unique_id' => $this->uniqueId]);
		}
	}

	/**
	 * Returns the unique ID as a string.
	 *
	 * @return string the uniqueId property of the object
	 */
	public function __toString()
	{
		return $this->uniqueId;
	}

	/**
	 * Creates an unique ID value object.
	 */
	public static function create(): static
	{
		$uid     = '';
		$length  = random_int(self::LENGTH['min'], self::LENGTH['max']);
		$decimal = hexdec(uniqid());
		$set     = 0;

		for ($i=1; $i <= $length; ++$i) {
			$charSet       = self::CHARACTERS[$set];
			$charSetLength = \strlen($charSet);
			$pow           = $charSetLength ** random_int(1, $length);
			$offset        = floor($decimal / $pow) % $charSetLength;
			$uid .= substr($charSet, $offset, 1);

			++$set;

			if (3 === $set) {
				$set = 0;
			}
		}

		return new static($uid);
	}

	/**
	 * Creates an unique ID value object from the given unique ID.
	 */
	public static function createFromString(string $uniqueId): static
	{
		return new static($uniqueId);
	}

	/**
	 * Validates the given unique ID.
	 */
	private function isValid(string $uniqueId): bool
	{
		$length = mb_strlen($uniqueId);

		return $length >= self::LENGTH['min'] && $length <= self::LENGTH['max'] && ctype_alnum($uniqueId);
	}
}
