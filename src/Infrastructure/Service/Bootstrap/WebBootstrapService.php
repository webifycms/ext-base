<?php

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
/**
 * WebBootstrapService
 * 
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class WebBootstrapService extends BootstrapService
{
    private WebApplicationServiceInterface $app;

    /**
     * Initialize the web bootstrap service.
     * 
     * Note: If you override this method, you should call the parent implementation on top of it.
     *
     * @param WebApplicationServiceInterface $app
     */
    public function init(WebApplicationServiceInterface $app): void
    {
        $this->app = $app;

        if ($this instanceof RegisterControllersBootstrapInterface) {
            $app->setApplicaitonProperty(
                'controllerMap',
                array_merge($app->getApplicaitonProperty('controllerMap'), $this->controllers())
            );
        }

        if ($this instanceof RegisterRoutesBootstrapInterface) {
            $app->getApplication()->getUrlManager()->addRules($this->routes(), false);
        }
    }

    /**
     * Returns the web application service instance.
     *
     * @return WebApplicationServiceInterface
     */
    public function getApplication(): WebApplicationServiceInterface
    {
        return $this->app;
    }
}
