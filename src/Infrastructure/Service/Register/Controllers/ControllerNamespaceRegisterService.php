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
 * Abstract class responsible for registering controller namespaces.
 * This class should be extended by all classes that register controller namespaces.
 */
abstract class ControllerNamespaceRegisterService implements PreRegisterServiceInterface, ControllerNamespaceRegisterServiceInterface
{
	/**
	 * Controller namespace config key.
	 */
	private const CONFIG_KEY = 'framework.controllerNamespaces';

	final public function register(Container $container): void
	{
		/**
		 * @var ConfigServiceInterface $configService
		 */
		$configService        = $container->get(ConfigServiceInterface::class);
		$namespaces           = $configService->getConfig(self::CONFIG_KEY, []);

		$configService->setConfig(
			self::CONFIG_KEY,
			array_merge($namespaces, $this->getNamespaces())
		);
	}
}
