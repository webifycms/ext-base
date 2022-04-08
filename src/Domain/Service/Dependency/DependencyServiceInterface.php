<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Dependency;

/**
 * Interface DependencyServiceInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface DependencyServiceInterface
{
    /**
     * Returns the dependency injection provider's container object.
     *
     * @return object
     */
    public function getContainer(): object;
}
