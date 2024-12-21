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

namespace Webify\Base\Domain\Model;

/**
 * An interface that is used to check if the object is blocked and returns the blocked at datetime
 * as object or string for the given format.
 */
interface CanBlockModelInterface
{
	/**
	 * If it is blocked returns true, otherwise false.
	 */
	public function isBlocked(): bool;

	/**
	 * Returns the blocked at datetime string for the given or default format.
	 */
	public function getBlockedAt(string $format): ?string;
}
