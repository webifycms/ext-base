<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Environment;

use ValueError;
use Webify\Base\Infrastructure\Exception\ApplicationEnvironmentException;

/**
 * The environment type.
 */
enum Type: string
{
	case Production  = 'production';
	case Development = 'development';

	/**
	 * Checks if the environment is production.
	 */
	public function isProduction(): bool
	{
		return self::Production === $this;
	}

	/**
	 * Checks if the environment is development.
	 */
	public function isDevelopment(): bool
	{
		return self::Development === $this;
	}

	/**
	 * Factory method to create an environment type from a string.
	 */
	public static function fromString(string $value): self
	{
		try {
			return self::from($value);
		} catch (ValueError) {
			throw ApplicationEnvironmentException::notDefinedInConfig();
		}
	}
}
