<?php

declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service\Bootstrap;

use Webify\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;

/**
 * ConsoleBootstrapService.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
class ConsoleBootstrapService implements BootstrapServiceInterface, ConsoleBootstrapServiceInterface
{
	/**
	 * @param ApplicationServiceInterface|WebApplicationServiceInterface $appService
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		private readonly ApplicationServiceInterface|ConsoleApplicationServiceInterface $appService,
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
	public function getApplicationService(): ApplicationServiceInterface|ConsoleApplicationServiceInterface
	{
		return $this->appService;
	}

	/**
	 * {@inheritDoc}
	 */
	public function init(): void
	{
	}
}
