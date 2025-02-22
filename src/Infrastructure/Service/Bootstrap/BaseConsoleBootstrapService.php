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

use Webify\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\di\Container;

/**
 * Console application bootstrap service class that helps to bootstrap components.
 */
abstract class BaseConsoleBootstrapService implements BootstrapServiceInterface, ConsoleBootstrapServiceInterface
{
	/**
	 * The object constructor.
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		private readonly ConfigServiceInterface $configService
	) {
		/**
		 * @var Container $container
		 */
		$container = $this->dependencyService->getContainer();

		$this->registerDependencies($container);
		$this->registerControllers($this->configService);
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

	private function registerControllers(ConfigServiceInterface $configService): void
	{
		if ($this instanceof RegisterControllerMapBootstrapInterface) {
			$controllerMap = array_merge(
				$configService->getConfig('framework.controllerMap', []),
				$this->controllerMap()
			);

			$configService->setConfig('framework.controllerMap', $controllerMap);
		}
	}
}
