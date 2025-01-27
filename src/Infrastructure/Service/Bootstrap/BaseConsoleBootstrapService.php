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
use Webify\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;
use yii\console\Application;

/**
 * Console application bootstrap service class that helps to bootstrap components.
 */
abstract class BaseConsoleBootstrapService implements BootstrapServiceInterface, ConsoleBootstrapServiceInterface
{
	/**
	 * The object constructor.
	 */
	public function __construct(
		private readonly ConfigServiceInterface $configService,
		private readonly ConsoleApplicationServiceInterface $consoleApplicationService,
	) {
		$this->registerDependencies();
		$this->registerControllers();
	}

	public function getApplication(): Application
	{
		return $this->consoleApplicationService->getApplication();
	}

	private function registerDependencies(): void
	{
		if ($this instanceof RegisterDependencyBootstrapInterface) {
			$dependencies = array_merge(
				$this->configService->getConfig('framework.container', []),
				$this->dependencies()
			);

			$this->configService->setConfig('framework.container', $dependencies);
		}
	}

	private function registerControllers(): void
	{
		if ($this instanceof RegisterControllersBootstrapInterface) {
			$controllerMap = array_merge(
				$this->configService->getConfig('framework.controllerMap', []),
				$this->controllers()
			);

			$this->configService->setConfig('framework.controllerMap', $controllerMap);
		}
	}
}
