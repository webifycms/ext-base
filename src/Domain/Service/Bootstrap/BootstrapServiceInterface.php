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

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;

/**
 * BootstrapServiceInterface.
 */
interface BootstrapServiceInterface
{
	/**
	 * Bootstrap the service needs.
	 */
	public function bootstrap(ApplicationServiceInterface $appService): void;
}
