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

namespace Webify\Base\Infrastructure\Service\Register;

use yii\di\Container;

/**
 * Register services implements `PreRegisterServiceInterface` will be registered before application run.
 * Examples like when needs to register dependencies, modify configuration, register routes ect...
 */
interface PreRegisterServiceInterface
{
	/**
	 * Register the service.
	 */
	public function register(Container $container): void;
}
