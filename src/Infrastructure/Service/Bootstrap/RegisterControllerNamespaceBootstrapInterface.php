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

namespace Webify\Base\Infrastructure\Service\Bootstrap;

/**
 * Interface RegisterControllerNamespaceBootstrapInterface.
 */
interface RegisterControllerNamespaceBootstrapInterface
{
	/**
	 * @return array<string>
	 */
	public function controllerNamespaces(): array;
}
