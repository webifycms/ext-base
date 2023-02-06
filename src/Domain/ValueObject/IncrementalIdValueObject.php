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

/**
 * Incremental id value object.
 */
abstract class IncrementalIdValueObject
{
	/**
	 * The object constructor.
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
