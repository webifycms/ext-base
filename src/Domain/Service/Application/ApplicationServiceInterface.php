<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2022 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author  Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace Webify\Base\Domain\Service\Application;

use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;

/**
 * ApplicationServiceInterface.
 */
interface ApplicationServiceInterface
{
	/**
	 * Start the application.
	 */
	public function start(): void;

	/**
	 * Returns the config as an array.
	 *
	 * @return array<string, mixed>
	 */
	public function getConfig(): array;

	/**
	 * Returns the dependency service instance.
	 */
	public function getDependency(): DependencyServiceInterface;
}
