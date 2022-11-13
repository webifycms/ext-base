<?php
/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model;

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
	 *
	 * @param null|string $format if the format is null will be use default format.
	 */
	public function getTrashedAt(?string $format = null): ?string;
}
