<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author  Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\Service\Config;

/**
 * Interface ConfigServiceInterface.
 *
 * Provides an abstraction for managing configuration settings.
 * Allows setting new configuration values, overriding existing ones,
 * and retrieving configuration values based on a key. The keys can represent
 * a hierarchy separated by periods.
 */
interface ConfigServiceInterface
{
	/**
	 * Set a new configuration or override existing configuration value for the given key.
	 *
	 * @param string $key the config key can support any deep,
	 *                    you must separate with the period (e.g. "framework.component.user")
	 */
	public function setConfig(string $key, mixed $config): self;

	/**
	 * Retrieve the configuration value for the given key, if not found will return the given default value.
	 * If the key is not specified the entire configurations will return.
	 *
	 * @param ?string $key the config key can support any deep,
	 *                     you must separate with the period (e.g. "framework.component.user").
	 */
	public function getConfig(?string $key, mixed $default): mixed;
}
