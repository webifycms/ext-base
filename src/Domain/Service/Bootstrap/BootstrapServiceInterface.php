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

namespace Webify\Base\Domain\Service\Bootstrap;

use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;

/**
 * BootstrapServiceInterface.
 */
interface BootstrapServiceInterface
{
	/**
	 * Initialize the bootstrap service.
	 */
	public function init(): void;

	/**
	 * Returns the dependency service instance.
	 */
	public function getDependencyService(): DependencyServiceInterface;
}
