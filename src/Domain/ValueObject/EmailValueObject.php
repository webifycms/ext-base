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
 * EmailValueObject will help to holds and validates an email address.
 */
final class EmailValueObject
{
	/**
	 * The object constructor.
	 *
	 * @throws InvalidEmailException
	 */
	private function __construct(
		private readonly string $email
	) {
		if (!$this->isValid($email)) {
			throw new InvalidEmailException('invalid_email', ['email' => $email]);
		}
	}

	/**
	 * The object can be used as string.
	 */
	public function __toString(): string
	{
		return $this->email;
	}

	/**
	 * Returns email string.
	 */
	public function getValue(): string
	{
		return $this->email;
	}

	/**
	 * Generates email value object from the given email address.
	 */
	public static function generate(string $email): self
	{
		return new self($email);
	}

	/**
	 * Validates the given email address and throws an exception if validation failed.
	 */
	private function isValid(string $email): bool
	{
		return !filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}
