<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Dependency;

/**
 * Interface DependencyRegisterInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface DependencyRegisterInterface
{
    public function dependencies(): array;
}