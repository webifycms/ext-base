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

namespace Webify\Base\Infrastructure\Service\Register\ViewParams;

use Webify\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Register\PostRegisterServiceInterface;

abstract class ViewParamsRegisterService implements PostRegisterServiceInterface, ViewParamsRegisterServiceInterface
{
	final public function register(ConsoleApplicationServiceInterface|WebApplicationServiceInterface $appService): void
	{
		if ($appService instanceof WebApplicationServiceInterface) {
			$appService->getApplication()->getView()->params[$this->getKey()] = $this->getParams();
		}
	}
}
