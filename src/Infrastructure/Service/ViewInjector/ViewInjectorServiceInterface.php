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

namespace Webify\Base\Infrastructure\Service\ViewInjector;

interface ViewInjectorServiceInterface
{
	/**
	 * Collects and processes the provided data array.
	 *
	 * @param array<string, mixed> $data the reference to the array that will be collected and modified
	 */
	public function collect(array &$data): void;
}
