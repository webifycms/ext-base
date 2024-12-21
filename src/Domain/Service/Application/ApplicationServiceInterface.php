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

namespace Webify\Base\Domain\Service\Application;

use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;

/**
 * Defines the contract for the application service.
 */
interface ApplicationServiceInterface
{
	/**
	 * The default administration path.
	 */
	public const DEFAULT_ADMINISTRATION_PATH = 'administration';

	/**
	 * Starts the application.
	 */
	public function bootstrap(): void;

	/**
	 * Retrieve the configuration value for the given key, if not found will return the given default value.
	 * If the key is not specified the entire configurations will return.
	 *
	 * @param ?string $key the config key can support any deep, you must separate with the period (e.g. "framework.component.user").
	 */
	public function getConfig(?string $key, mixed $default): mixed;

	/**
	 * Returns the dependency service instance.
	 *
	 * @todo Should finds out, weather is it needed in the application service.
	 */
	public function getDependency(): DependencyServiceInterface;
}
