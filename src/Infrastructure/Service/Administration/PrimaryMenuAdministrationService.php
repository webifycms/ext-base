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

use yii\web\View;

use function in_array;

/**
 * The service helps to register primary menu items to the view.
 */
final class PrimaryMenuAdministrationService implements PrimaryMenuAdministrationServiceInterface
{
	/**
	 * @var array<string>
	 */
	private array $menuItemsServices = [];

	public function __construct(
		private readonly View $view,
	) {}

	public function register(PrimaryMenuItemsAdministrationServiceInterface $menuItemsService): void
	{
		if (in_array($menuItemsService::class, $this->menuItemsServices, true)) {
			return;
		}

		$this->menuItemsServices[] = $menuItemsService::class;

		$this->addItems($menuItemsService);
	}

	/**
	 * Add menu items to view component for the given menu items service.
	 */
	private function addItems(PrimaryMenuItemsAdministrationServiceInterface $menuItemService): void
	{
		$items = array_merge(
			$this->view->params[self::PRIMARY_MENU_KEY] ?? [],
			$menuItemService->getItems()
		);

		usort(
			$items,
			fn ($a, $b) => ($a['position'] ?? self::DEFAULT_SORT_ORDER)
				<=> ($b['position'] ?? self::DEFAULT_SORT_ORDER)
		);

		$this->view->params[self::PRIMARY_MENU_KEY] = $items;
	}
}
