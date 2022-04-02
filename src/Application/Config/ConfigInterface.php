<?php
declare(strict_types=1);

namespace OneCMS\Base\Application\Config;

/**
 * Interface ConfigInterface
 *
 * @package getonecms/application
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface ConfigInterface
{

    /**
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, $value): void;

    /**
     * @return mixed
     */
    public function get(?string $key = null);
}
