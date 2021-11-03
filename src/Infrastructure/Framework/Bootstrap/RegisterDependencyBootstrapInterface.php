<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Bootstrap;

/**
 * Interface RegisterDependencyBootstrapInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface RegisterDependencyBootstrapInterface
{
    /**
     * @return array
     */
    public function dependencies(): array;
}