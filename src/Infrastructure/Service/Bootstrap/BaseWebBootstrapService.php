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

namespace Webify\Base\Infrastructure\Service\Bootstrap;

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\di\Container;
use yii\web\GroupUrlRule;
use yii\web\UrlRule;

/**
 * Web application bootstrap service class that helps to bootstrap components.
 */
abstract class BaseWebBootstrapService implements BootstrapServiceInterface, WebBootstrapServiceInterface
{
	/**
	 * The object constructor.
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		private readonly ConfigServiceInterface $configService,
	) {
		/**
		 * @var Container $container
		 */
		$container = $this->dependencyService->getContainer();

		$this->registerDependencies($container);
		$this->registerControllerNamespaces($this->configService);
		$this->registerRoutes($this->configService);
		$this->registerAdminRoutes($this->configService);
	}

	private function registerDependencies(Container $container): void
	{
		if ($this instanceof RegisterDependenciesBootstrapInterface) {
			$dependencies = $this->dependencies();

			if (isset($dependencies['definitions'])) {
				$container->setDefinitions($dependencies['definitions']);
			}

			if (isset($dependencies['singletons'])) {
				$container->setSingletons($dependencies['singletons']);
			}
		}
	}

	private function registerRoutes(ConfigServiceInterface $configService): void
	{
		if ($this instanceof RegisterRoutesBootstrapInterface) {
			$existingRoutes = $configService->getConfig('framework.components.urlManager.rules', []);

			$configService->setConfig(
				'framework.components.urlManager.rules',
				array_merge($existingRoutes, $this->routes())
			);
		}
	}

	private function registerAdminRoutes(ConfigServiceInterface $configService): void
	{
		if ($this instanceof RegisterAdminRoutesBootstrapInterface) {
			$groupRule = new GroupUrlRule([
				'prefix' => $configService->getConfig(
					'administrationPath',
					ApplicationServiceInterface::DEFAULT_ADMINISTRATION_PATH
				),
				'routePrefix' => '',
				'ruleConfig'  => [
					'class'  => UrlRule::class,
					'suffix' => '',
				],
				'rules' => $this->adminRoutes(),
			]);
			$existingRoutes = $configService->getConfig('framework.components.urlManager.rules', []);

			$configService->setConfig(
				'framework.components.urlManager.rules',
				array_merge($existingRoutes, [$groupRule])
			);
		}
	}

	private function registerControllerNamespaces(ConfigServiceInterface $configService): void
	{
		if ($this instanceof RegisterControllerNamespaceBootstrapInterface) {
			$controllerMap = $configService->getConfig('framework.controllerNamespaces', []);

			$configService->setConfig(
				'framework.controllerNamespaces',
				array_merge($controllerMap, $this->controllerNamespaces())
			);
		}
	}
}
