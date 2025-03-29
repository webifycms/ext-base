<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\ValueObject;

use Throwable;

/**
 * Incremental id value object.
 */
abstract class IncrementalIdValueObject
{
	/**
	 * The object constructor.
	 *
	 * @throws Throwable
	 */
	final private function __construct(
		private readonly int $id
	) {
		if (!$this->isValid($this->id)) {
			$this->throwException(['id' => (string) $id]);
		}
	}

	/**
	 * It helps to pass the object as string.
	 */
	public function __toString(): string
	{
		return (string) $this->id;
	}

	/**
	 * Factory method to create this object.
	 *
	 * @throws Throwable
	 */
	public static function create(int $id): static
	{
		return new static($id);
	}

	/**
	 * It helps to throw custom exceptions according to the context when validation failed.
	 *
	 * @param string[] $params additional params that can be used in the exception message
	 */
	abstract protected function throwException(array $params): void;

	/**
	 * Validates the given id.
	 */
	private function isValid(int $id): bool
	{
		return 0 < $id;
	}
}
