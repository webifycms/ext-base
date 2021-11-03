<?php

namespace OneCMS\Base\Domain\Behaviour\Blockable;

/**
 * Interface BlockableBehaviourInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface BlockableBehaviourInterface
{
    /**
     * @return bool
     */
    public function isBlocked(): bool;
}