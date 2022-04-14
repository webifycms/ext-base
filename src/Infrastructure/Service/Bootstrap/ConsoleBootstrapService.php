<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use OneCMS\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;

/**
 * ConsoleBootstrapService
 * 
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class ConsoleBootstrapService implements BootstrapServiceInterface, ConsoleBootstrapServiceInterface
{
    /**
     * @param DependencyServiceInterface $dependencyService
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
     * @inheritDoc
     */
    public function getDependencyService(): DependencyServiceInterface
    {
        return $this->dependencyService;
    }

    /**
     * @inheritDoc
     */
    public function getApplicationService(): ApplicationServiceInterface|ConsoleApplicationServiceInterface
    {
        return $this->appService;
    }

    /**
     * @inheritDoc
     */
    public function init(): void
    {
    }
}
