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
 * A value object that represents an IP address.
 */
abstract class IpValueObject
{
	/**
	 * The object constructor.
	 *
	 * @throws Throwable
	 */
	final public function __construct(
		public readonly string $ip
	) {
		if (!$this->isValid($this->ip)) {
			$this->throwException(['ip' => $ip]);
		}
	}

	/**
	 * Allow to use this object as a string type.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->ip;
	}

	/**
	 * Creates IP address value object for the given IP.
	 *
	 * @throws Throwable
	 */
	public static function create(string $ip): static
	{
		return new static($ip);
	}

	/**
	 * It helps to throw custom exceptions according to the context when validation failed.
	 *
	 * @param string[] $params additional params that can be used in the exception message
	 */
	abstract protected function throwException(array $params): void;

	/**
	 * Validates the given IP address.
	 */
	private function isValid(string $ip): bool
	{
		return is_string(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4|FILTER_FLAG_IPV6));
	}
}
