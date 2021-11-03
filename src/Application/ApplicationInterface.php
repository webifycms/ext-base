<?php
declare(strict_types=1);

namespace OneCMS\Base\Application;

use OneCMS\Base\Application\Config\ConfigInterface;
use OneCMS\Base\Application\Dependency\DependencyInterface;

/**
 * Interface ApplicationInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface ApplicationInterface
{
    /**
     * @return mixed
     */
    public function bootstrap();

    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface;

    /**
     * @return DependencyInterface
     */
    public function getDependency(): DependencyInterface;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name);

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value): void;
}