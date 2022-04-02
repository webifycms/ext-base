<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Bootstrap;


use OneCMS\Base\Infrastructure\Framework\Dependency\DependencyInterface;
use OneCMS\Base\Infrastructure\Framework\Web\Application\WebApplicationInterface;

/**
 * Class BaseBootstrap
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
abstract class AbstractBootstrap
{
    public function __construct(private readonly DependencyInterface $dependency)
    {
        if ($this instanceof RegisterDependencyBootstrapInterface) {
            $this->dependency->getContainer()->setdefinitions($this->dependencies());
        }
    }

    public function getDependency(): DependencyInterface
    {
        return $this->dependency;
    }

    /**
     * Initialize
     * Note: If you override this method, you should call parent implementation on top of it.
     */
    public function init(WebApplicationInterface $app): void
    {
        if ($this instanceof RegisterControllersBootstrapInterface) {
            $app->set(
                'controllerMap', array_merge($app->get('controllerMap'), $this->controllers())
            );
        }

        if ($this instanceof RegisterRoutesBootstrapInterface) {
            $app->getComponent()->getUrlManager()->addRules($this->routes(), false);
        }
    }
}