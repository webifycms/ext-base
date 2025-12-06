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

namespace Webify\Base\Infrastructure\Service\Register\Routes;

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Infrastructure\Service\Register\PreRegisterServiceInterface;
use yii\di\Container;
use yii\web\GroupUrlRule;
use yii\web\UrlRule;

/**
 * Abstract class responsible for registering routes.
 * This class should be extended by all classes that register routes.
 */
abstract class RoutesRegisterService implements PreRegisterServiceInterface, RoutesRegisterServiceInterface
{
	final public function register(Container $container): void
	{
		/**
		 * @var ConfigServiceInterface $configService
		 */
		$configService = $container->get(ConfigServiceInterface::class);

		if (!$this instanceof AdminRoutesRegisterServiceInterface) {
			$this->registerNormalRoutes($configService);

			return;
		}

		$this->registerAdminRoutes($configService);
	}

	private function registerNormalRoutes(ConfigServiceInterface $configService): void
	{
		$existingRoutes = $configService->getConfig('framework.components.urlManager.rules', []);

		$configService->setConfig(
			'framework.components.urlManager.rules',
			array_merge($existingRoutes, $this->getRoutes())
		);
	}

	private function registerAdminRoutes(ConfigServiceInterface $configService): void
	{
		$groupRule = new GroupUrlRule([
			'prefix'      => $configService->getConfig(
				'administrationPath',
				ApplicationServiceInterface::DEFAULT_ADMINISTRATION_PATH
			),
			'routePrefix' => '',
			'ruleConfig'  => [
				'class'  => UrlRule::class,
				'suffix' => '',
			],
			'rules'       => $this->getRoutes(),
		]);
		$existingRoutes = $configService->getConfig('framework.components.urlManager.rules', []);

		$configService->setConfig(
			'framework.components.urlManager.rules',
			array_merge($existingRoutes, [$groupRule])
		);
	}
}
