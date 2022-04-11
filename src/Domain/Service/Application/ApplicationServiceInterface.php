<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Application;

use OneCMS\Base\Application\Config\ConfigInterface;
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
     * @return mixed
     */
    public function start();

    public function getConfig(): array;

    public function getDependency(): DependencyServiceInterface;
}
