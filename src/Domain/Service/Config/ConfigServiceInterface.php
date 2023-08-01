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
 * The `ConfigServiceInterface` is an interface that defines the methods that a config service class should implement.
 */
interface ConfigServiceInterface
{
	/**
	 * Set a new configuration or override existing configuration value for the given key.
	 *
	 * @param string $key the config key can support any deep, you must separate with the period (e.g. "framework.component.user")
	 */
	public function setConfig(string $key, mixed $config): self;

	/**
	 * Retrieve the configuration value for the given key, if not found will return the given default value.
	 * If the key is not specified the entire configurations will return.
	 *
	 * @param ?string $key the config key can support any deep, you must separate with the period (e.g. "framework.component.user").
	 */
	public function getConfig(?string $key, mixed $default): mixed;
}
