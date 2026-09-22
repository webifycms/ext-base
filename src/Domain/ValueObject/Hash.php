<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\ValueObject;

use Webify\Base\Domain\Exception\InvalidHashException;

/**
 * Hash value object.
 *
 * Generates a compact, deterministic hash from an algorithm identifier and input data.
 */
final readonly class Hash
{
	/**
	 * The default maximum allowed length of the generated hash.
	 */
	private const int DEFAULT_MAX_LENGTH = 6;

	/**
	 * The constructor.
	 *
	 * @throws InvalidHashException if the hash is not valid
	 */
	private function __construct(private string $value)
	{
		if (!$this->isValid()) {
			throw InvalidHashException::forInvalidHash();
		}
	}

	/**
	 * String representation of the hash.
	 */
	public function __toString(): string
	{
		return $this->value;
	}

	/**
	 * Generates a compact, deterministic hash from the given algorithm and data.
	 *
	 * The hash is produced using PHP's `hash()` function and truncated to the
	 * requested length.
	 *
	 * @param string $algorithm the hash algorithm identifier (e.g. "crc32", "sha256")
	 * @param string $value     the value to be hashed
	 * @param int    $length    the maximum length of the hash, defauts to 6
	 */
	public static function generate(string $algorithm, string $value, int $length = self::DEFAULT_MAX_LENGTH): self
	{
		return new self(
			substr(hash($algorithm, $value), 0, $length)
		);
	}

	/**
	 * Factory method to create an instance from a known hash string.
	 */
	public static function fromString(string $value): self
	{
		return new self($value);
	}

	/**
	 * Equality check based on the value.
	 */
	public function equals(self $other): bool
	{
		return $this->toNative() === $other->toNative();
	}

	/**
	 * Get the native string value of the hash.
	 */
	public function toNative(): string
	{
		return $this->value;
	}

	/**
	 * Validates the value.
	 */
	private function isValid(): bool
	{
		return '' !== $this->value;
	}
}
