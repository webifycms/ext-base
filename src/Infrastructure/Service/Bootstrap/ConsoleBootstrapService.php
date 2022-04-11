<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;
/**
 * ConsoleBootstrapService
 * 
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class ConsoleBootstrapService extends BootstrapService
{
    private ConsoleApplicationServiceInterface $app;

    /**
     * Initialize
     * Note: If you override this method, you should call the parent implementation on top of it.
     */
    public function init(ConsoleApplicationServiceInterface $app): void
    {
        $this->app = $app;

        if ($this instanceof RegisterControllersBootstrapInterface) {
            $app->set(
                'controllerMap',
                array_merge($app->get('controllerMap'), $this->controllers())
            );
        }
    }

    /**
     * Returns the console application service instance.
     *
     * @return ConsoleApplicationServiceInterface
     */
    public function getApplication(): ConsoleApplicationServiceInterface
    {
        return $this->app;
    }
}
