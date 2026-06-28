<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Contract;

/**
 * ServiceProviderInterface defines how extensions integrate into the application.
 *
 * `getDefinitions()` runs before the container is built — register bindings here.
 */
interface ServiceProviderInterface
{
	/**
	 * Get the definitions of the services.
	 *
	 * @return array<string, mixed> the definitions
	 */
	public function getDefinitions(): array;
}
