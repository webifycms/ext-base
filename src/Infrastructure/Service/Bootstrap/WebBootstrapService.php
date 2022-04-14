<?php

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

/**
 * WebBootstrapService
 * 
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class WebBootstrapService implements BootstrapServiceInterface, WebBootstrapServiceInterface
{
    /**
     * @param DependencyServiceInterface $dependencyService
     * @param ApplicationServiceInterface|WebApplicationServiceInterface $appService
     */
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
     * @inheritDoc
     */
    public function getDependencyService(): DependencyServiceInterface
    {
        return $this->dependencyService;
    }

    /**
     * @inheritDoc
     */
    public function getApplicationService(): ApplicationServiceInterface|WebApplicationServiceInterface
    {
        return $this->appService;
    }

    /**
     * @inheritDoc
     * 
     * Note: If you override this method, you should call the parent implementation on top of it.
     */
    public function init(): void
    {
    }
}
