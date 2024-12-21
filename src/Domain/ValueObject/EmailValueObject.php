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

use Webify\Base\Domain\Service\Validator\EmailValidatorServiceInterface;

/**
 * A value object that represents an email address.
 */
abstract class EmailValueObject
{
	/**
	 * The object constructor.
	 *
	 * @throws \Throwable
	 */
	final private function __construct(
		private readonly string $email,
		private readonly EmailValidatorServiceInterface $validator
	) {
		if (!$this->validator->isValid($email)) {
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
	 *
	 * @throws \Throwable
	 */
	public static function create(string $email, EmailValidatorServiceInterface $validator): static
	{
		return new static($email, $validator);
	}

	/**
	 * It helps to throw custom exceptions according to the context when validation failed.
	 *
	 * @param string[] $params additional params that can be used in the exception message
	 */
	abstract protected function throwException(array $params): void;
}
