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

namespace Webify\Base\Infrastructure\Service\Register\Controllers;

use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Infrastructure\Service\Register\PreRegisterServiceInterface;
use yii\di\Container;

/**
 * Abstract class responsible for registering a controller map.
 * This class should be extended by all classes that register a controller map.
 */
abstract class ControllerMapRegisterService implements PreRegisterServiceInterface, ControllerMapRegisterServiceInterface
{
	/**
	 * Controller map config key.
	 *
	 * @const string
	 */
	private const CONFIG_KEY = 'framework.controllerMap';

	final public function register(Container $container): void
	{
		/**
		 * @var ConfigServiceInterface $configService
		 */
		$configService        = $container->get(ConfigServiceInterface::class);
		$controllerMap        = array_merge(
			$configService->getConfig(self::CONFIG_KEY, []),
			$this->getMap()
		);

		$configService->setConfig(self::CONFIG_KEY, $controllerMap);
	}
}
