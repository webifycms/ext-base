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
 * A value object that represents an email address.
 */
abstract class EmailValueObject
{
	/**
	 * The object constructor.
	 */
	final private function __construct(
		private readonly string $email
	) {
		if (!$this->isValid($email)) {
			$this->throwException(['email' => $email]);
		}
	}

	/**
	 * The __toString() function is a magic method that is called when the object is used in a string
	 * context.
	 *
	 * @return string The email address
	 */
	public function __toString()
	{
		return $this->email;
	}

	/**
	 * Creates email value object from the given email address.
	 */
	public static function create(string $email): static
	{
		return new static($email);
	}

	/**
	 * It helps to throw custom exceptions according to the context when validation failed.
	 *
	 * @param string[] $params additional params that can be used in the exception message
	 */
	abstract protected function throwException(array $params): void;

	/**
	 * Validates the given email address.
	 *
	 * @todo Regex should be verified.
	 */
	private function isValid(string $email): bool
	{
		if (preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,5})$/', $email)) {
			return true;
		}

		return false;
	}
}
