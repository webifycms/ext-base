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
 * Interface for the base extension.
 */
interface BaseExtensionInterface extends ExtensionInterface
{
	/**
	 * The extension name.
	 */
	public const NAME = 'Base';

	/**
	 * The extension version.
	 */
	public const VERSION = '0.0.1';
}
