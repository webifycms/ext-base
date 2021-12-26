<?php

namespace OneCMS\Base\Domain\Behaviour\Blockable;

/**
 * Interface BlockableInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface BlockableInterface
{
    /**
     * @return bool
     */
    public function isBlocked(): bool;
}