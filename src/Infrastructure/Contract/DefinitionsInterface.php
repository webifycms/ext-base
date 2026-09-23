<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Contract;

/**
 * DefinitionsInterface groups related container definitions into one class.
 *
 * Every definition group (HTTP, logging, eventing, theming, persistence, …)
 * exposes them through `getDefinitions()` so the composition root stays a
 * thin merge and each group is isolated and testable on its own.
 */
interface DefinitionsInterface
{
	/**
	 * Get the container definitions of the group.
	 *
	 * @return array<string, mixed> the PHP-DI definitions
	 */
	public static function getDefinitions(): array;
}
