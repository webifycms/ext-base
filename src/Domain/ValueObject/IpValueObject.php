<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @license Copyright (c) 2022 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\ValueObject;

use Webify\Base\Domain\Exception\InvalidIpException;

/**
 * A value object that represents an IP address.
 */
abstract class IpValueObject
{
	/**
	 * The object constructor.
	 *
	 * @throws InvalidIpException
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
	 */
	public static function create(string $ip): static
	{
		return new static($ip);
	}

	/**
	 * It helps to throw custom exceptions according to the context when validation failed.
	 *
	 * @param string[] $params Additional params that can be used in the exception message.
	 */
	abstract protected function throwException(array $params): void;

	/**
	 * Validates the given IP address.
	 */
	private function isValid(string $ip): bool
	{
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4|FILTER_FLAG_IPV6);
	}
}
