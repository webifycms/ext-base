<?php

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;

/**
 * BootstrapService
 * 
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
abstract class BootstrapService implements BootstrapServiceInterface
{
    /**
     * @param DependencyServiceInterface $dependency
     */
    public function __construct(private readonly DependencyServiceInterface $dependency)
    {
        if ($this instanceof RegisterDependencyBootstrapInterface) {
            $this->dependency->getContainer()->setdefinitions($this->dependencies());
        }
    }

    /**
     * @inheritDoc
     */
    public function getDependency(): DependencyServiceInterface
    {
        return $this->dependency;
    }
}
