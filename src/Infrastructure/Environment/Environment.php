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

namespace Webify\Base\Infrastructure\Environment;

use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Exception\ApplicationEnvironmentException;

/**
 * Environment represents the current environment of the application.
 */
final readonly class Environment
{
	/**
	 * Private constructor to enforce the use of the factory method.
	 */
	private function __construct(
		private Type $type,
		private bool $debug
	) {}

	/**
	 * Factory method to prepare the environment based on the configurations.
	 *
	 * @throws ApplicationEnvironmentException if the environment is not defined in the configurations
	 */
	public static function prepare(ConfigInterface $config): self
	{
		if (!$config->has('environment')) {
			throw ApplicationEnvironmentException::notDefinedInConfig();
		}

		return new self(
			Type::from($config->get('environment')),
			(bool) $config->get('debug', false)
		);
	}

	/**
	 * Checks if the environment is production.
	 */
	public function isProduction(): bool
	{
		return $this->type->isProduction();
	}

	/**
	 * Checks if the environment is development.
	 */
	public function isDevelopment(): bool
	{
		return $this->type->isDevelopment();
	}

	/**
	 * Checks the application in debug mode.
	 */
	public function isDebugEnabled(): bool
	{
		return $this->debug;
	}
}
