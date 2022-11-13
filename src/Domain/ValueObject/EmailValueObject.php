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

use OneCMS\Base\Domain\Exception\InvalidEmailException;

/**
 * It's a value object that represents an email address.
 */
class EmailValueObject
{
	/**
	 * The object constructor.
	 *
	 * @throws InvalidEmailException
	 */
	final public function __construct(
		private readonly string $email
	) {
		if (!$this->isValid($email)) {
			throw new InvalidEmailException('invalid_email', ['email' => $email]);
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
	 * Validates the given email address and throws an exception if validation failed.
	 *
	 * @todo In some cases FILTER_VALIDATE_EMAIL can fail, so in that case better use another way of validation.
	 */
	private function isValid(string $email): bool
	{
		return !filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}
