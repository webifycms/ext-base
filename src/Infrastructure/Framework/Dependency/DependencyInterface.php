<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Dependency;


/**
 * Interface DependencyManagerInterface
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface DependencyInterface extends \OneCMS\Base\Application\Dependency\DependencyInterface
{
    /**
     * @return mixed
     */
    public function get(string $class, array $params = [], array $config = []);
}