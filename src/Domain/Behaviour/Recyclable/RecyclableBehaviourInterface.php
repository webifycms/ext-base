<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Behaviour\Recyclable;

/**
 * Interface RecyclableBehaviourInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface RecyclableBehaviourInterface
{
    /**
     * @return bool
     */
    public function inTrashed(): bool;
}