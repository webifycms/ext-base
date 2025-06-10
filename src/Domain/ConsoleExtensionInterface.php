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

namespace Webify\Base\Domain;

/**
 * Interface for defining console register services for the extensions.
 */
interface ConsoleExtensionInterface
{
	/**
	 * Retrieves the list of the classes that needs to register in the console application.
	 *
	 * @return array<string>|array{}
	 */
	public function getConsoleRegisterServices(): array;
}
