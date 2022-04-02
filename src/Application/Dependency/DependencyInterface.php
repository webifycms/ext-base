<?php
declare(strict_types=1);

namespace OneCMS\Base\Application\Dependency;

/**
 * Interface DependencyInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface DependencyInterface
{
    public function getContainer(): object;
}