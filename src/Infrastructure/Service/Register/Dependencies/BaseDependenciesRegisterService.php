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

namespace Webify\Base\Infrastructure\Service\Register\Dependencies;

use function Webify\Base\Infrastructure\get_alias;

/**
 * The service class to register dependencies for the Base extension.
 */
final class BaseDependenciesRegisterService extends DependenciesRegisterService
{
	/**
	 * @return array<string, mixed>
	 */
	public function getDependencies(): array
	{
		return require get_alias('@Base/config/dependencies.php');
	}
}
