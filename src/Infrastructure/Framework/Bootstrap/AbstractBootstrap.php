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
    /**
     * @var DependencyInterface
     */
    private DependencyInterface $dependency;

    /**
     * @param DependencyInterface $dependency
     */
    public function __construct(DependencyInterface $dependency)
    {
        $this->dependency = $dependency;

        if ($this instanceof RegisterDependencyBootstrapInterface) {
            $this->dependency->getContainer()->setdefinitions($this->dependencies());
        }
    }

    /**
     * @return DependencyInterface
     */
    public function getDependency(): DependencyInterface
    {
        return $this->dependency;
    }

    /**
     * Initialize
     * Note: If you override this method, you should call parent implementation on top of it.
     *
     * @param WebApplicationInterface $app
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