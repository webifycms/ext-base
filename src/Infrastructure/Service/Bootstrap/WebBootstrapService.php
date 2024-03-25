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

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface as DomainApplicationServiceInterface;
use Webify\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\web\Application;

/**
 * Web application bootstrap service class that helps to bootstrap components.
 */
abstract class WebBootstrapService implements BootstrapServiceInterface, WebBootstrapServiceInterface
{
	/**
	 * The object constructor.
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		private readonly ApplicationServiceInterface|DomainApplicationServiceInterface|WebApplicationServiceInterface $appService,
	) {
		if ($this instanceof RegisterDependencyBootstrapInterface) {
			$dependencyService->getContainer()->setDefinitions($this->dependencies());
		}

		if ($this instanceof RegisterControllersBootstrapInterface) {
			$appService->setApplicationProperty(
				'controllerMap',
				array_merge($appService->getApplicationProperty('controllerMap'), $this->controllers())
			);
		}

		if ($this instanceof RegisterRoutesBootstrapInterface) {
			$appService->getApplication()->getUrlManager()->addRules($this->routes(), false);
		}
	}

	public function getApplication(): Application
	{
		return $this->appService->getApplication();
	}

	public function getDependencyService(): DependencyServiceInterface
	{
		return $this->dependencyService;
	}

	public function getApplicationService(): ApplicationServiceInterface|DomainApplicationServiceInterface|WebApplicationServiceInterface
	{
		return $this->appService;
	}

	abstract public function init(): void;
}
