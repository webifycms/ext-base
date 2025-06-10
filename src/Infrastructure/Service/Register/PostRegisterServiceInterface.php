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

use Webify\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

/**
 * Register services implements `PostRegisterServiceInterface` will be registered after application run.
 */
interface PostRegisterServiceInterface
{
	/**
	 * Register the service.
	 */
	public function register(ConsoleApplicationServiceInterface|WebApplicationServiceInterface $appService): void;
}
