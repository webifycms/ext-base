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
 * An interface that is used to check if the object is in trash and returns the trashed at datetime
 * string for the given or default format.
 */
interface RecyclableModelInterface
{
	/**
	 * Check weather the object is in trash and returns true, otherwise false.
	 */
	public function isInTrash(): bool;

	/**
	 * Returns the trashed at datetime string for the given or default format.
	 */
	public function getTrashedAt(string $format): ?string;
}
