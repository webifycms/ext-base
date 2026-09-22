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
 * TemplatePath value object class.
 *
 * Represents the template path.
 */
final readonly class TemplatePath
{
	/**
	 * The constructor.
	 */
	private function __construct(private string $value) {}

	/**
	 * Converts the object to its string representation.
	 */
	public function __toString(): string
	{
		return $this->value;
	}

	/**
	 * Factory method to create a template path value object from a string.
	 */
	public static function fromString(string $value): self
	{
		return new self($value);
	}

	/**
	 * Converts the object to its native string representation.
	 */
	public function toNative(): string
	{
		return $this->value;
	}
}
