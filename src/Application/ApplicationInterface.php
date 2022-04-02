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

    public function getConfig(): ConfigInterface;

    public function getDependency(): DependencyInterface;

    /**
     * @return mixed
     */
    public function get(string $name);

    /**
     * @param mixed $value
     */
    public function set(string $name, $value): void;
}