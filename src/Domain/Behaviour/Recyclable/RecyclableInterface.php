<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Behaviour\Recyclable;

/**
 * Interface RecyclableInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface RecyclableInterface
{
    /**
     * @return bool
     */
    public function inTrashed(): bool;
}