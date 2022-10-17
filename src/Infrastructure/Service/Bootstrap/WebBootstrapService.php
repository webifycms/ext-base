<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\web\Application;

/**
 * WebBootstrapService.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
class WebBootstrapService implements BootstrapServiceInterface, WebBootstrapServiceInterface
{
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		private readonly ApplicationServiceInterface|WebApplicationServiceInterface $appService,
	) {
		if ($this instanceof RegisterDependencyBootstrapInterface) {
			$dependencyService->getContainer()->setdefinitions($this->dependencies());
		}

		if ($this instanceof RegisterControllersBootstrapInterface) {
			$appService->setApplicaitonProperty(
				'controllerMap',
				array_merge($appService->getApplicaitonProperty('controllerMap'), $this->controllers())
			);
		}

		if ($this instanceof RegisterRoutesBootstrapInterface) {
			$appService->getApplication()->getUrlManager()->addRules($this->routes(), false);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependencyService(): DependencyServiceInterface
	{
		return $this->dependencyService;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getApplicationService(): ApplicationServiceInterface|WebApplicationServiceInterface
	{
		return $this->appService;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getApplication(): Application
	{
		return $this->appService->getApplication();
	}

	/**
	 * {@inheritDoc}
	 *
	 * Note: If you override this method, you should call the parent implementation on top of it.
	 */
	public function init(): void
	{
	}
}
