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
 * Interface RegisterDependencyBootstrapInterface.
 */
interface RegisterDependenciesBootstrapInterface
{
	/**
	 * Define dependencies.
	 *
	 * @return array<string, mixed>
	 */
	public function dependencies(): array;
}
