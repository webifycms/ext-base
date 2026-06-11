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

namespace Webify\Base\Test\Unit\Domain\ValueObject\Example;

use InvalidArgumentException;
use Webify\Base\Domain\ValueObject\SecureToken;

/**
 * ExampleSecureToken is a concrete implementation of the SecureToken for testing purposes.
 *
 * It provides a simple implementation of the throwException method required by the abstract class.
 *
 * @internal
 */
final readonly class ExampleSecureToken extends SecureToken
{
	/**
	 * {@inheritDoc}
	 */
	protected function throwException(string $value): never
	{
		throw new InvalidArgumentException('Invalid secure token: ' . $value);
	}
}
