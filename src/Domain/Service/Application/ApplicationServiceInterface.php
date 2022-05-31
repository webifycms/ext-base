<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Application;

use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;

/**
 * ApplicationServiceInterface
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface ApplicationServiceInterface
{
    /**
     * Start the application.
     */
    public function start();

    /**
     * Returns the config as an array.
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Returns the dependency service instance.
     *
     * @return DependencyServiceInterface
     */
    public function getDependency(): DependencyServiceInterface;
}
