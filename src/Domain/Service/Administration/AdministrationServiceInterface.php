<?php

/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @copyright Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author  Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Administration;

/**
 * AdministrationServiceInterface.
 */
interface AdministrationServiceInterface
{
	/**
	 * Returns the administration path.
	 */
	public function getPath(): string;

	/**
	 * Returns absolute url of the administration.
	 */
	public function getUrl(): string;

	/**
	 * Set administration menu items.
	 *
	 * @param mixed[] $items
	 */
	public function setMenuItems(array $items): void;

	/**
	 * Returns true if in administration, otherwise returns false.
	 */
	public function inAdministration(): bool;
}
