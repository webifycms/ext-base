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

namespace Webify\Base\Infrastructure\Service\Administration;

interface PrimaryMenuAdministrationServiceInterface
{
	/**
	 * Primary menu items key.
	 *
	 * @const string
	 */
	public const PRIMARY_MENU_KEY = 'primaryMenuItems';

	/**
	 * Default sort order number.
	 *
	 * @const int
	 */
	public const DEFAULT_SORT_ORDER = 10;

	/**
	 * Registers menu items into the collection if it is not already present.
	 */
	public function register(PrimaryMenuItemsAdministrationServiceInterface $menuItemsService): void;
}
