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

/**
 * ResourcePath value object base class.
 *
 * Represents the public-facing address of a domain resource as a path segment —
 * deliberately independent of scheme, host, or route prefix. The domain only knows
 * "where a resource lives within the site's structure"; Infrastructure is
 * responsible for turning this into an absolute URL.
 *
 * @phpstan-consistent-constructor
 */
abstract readonly class ResourcePath
{
	/**
	 * Resource path pattern format:
	 * - Must be lowercase.
	 * - Segments separated by a single forward slash.
	 * - Each segment contains only letters, numbers, and hyphens (same rules as Slug).
	 * - Must not start or end with a slash.
	 * - Must not contain consecutive slashes or consecutive hyphens within a segment.
	 */
	private const string FORMAT = '/^[a-z0-9]+(?:-[a-z0-9]+)*(?:\/[a-z0-9]+(?:-[a-z0-9]+)*)*$/';

	/**
	 * The protected constructor enforces the use of factory methods to initiate this object.
	 */
	protected function __construct(
		private string $value
	) {
		if (!$this->isValid()) {
			$this->throwException($this->value);
		}
	}

	/**
	 * Converts the object to its string representation.
	 */
	public function __toString(): string
	{
		return $this->value;
	}

	/**
	 * Factory method to create a canonical path value object from a string.
	 */
	public static function fromString(string $value): static
	{
		return new static($value);
	}

	/**
	 * Converts the object to its native string representation.
	 */
	public function toNative(): string
	{
		return $this->value;
	}

	/**
	 * Returns the individual segments of the path, e.g. "blog/my-post" -> ['blog', 'my-post'].
	 *
	 * @return string[]
	 */
	public function segments(): array
	{
		return explode('/', $this->value);
	}

	/**
	 * Returns the last segment of the path, e.g. "blog/my-post" -> "my-post".
	 */
	public function leaf(): string
	{
		$segments = $this->segments();

		return $segments[count($segments) - 1];
	}

	/**
	 * Compares the current object with another instance for equality.
	 *
	 * @param ResourcePath $other the instance to compare with the current object
	 *
	 * @return bool true if both instances are considered equal, false otherwise
	 */
	public function equals(self $other): bool
	{
		return $this->toNative() === $other->toNative();
	}

	/**
	 * Throws an exception as part of the implementation detail in derived classes.
	 */
	abstract protected function throwException(string $value): never;

	/**
	 * Validates the value against the predefined format pattern.
	 */
	private function isValid(): bool
	{
		return (bool) preg_match(self::FORMAT, $this->value);
	}
}
