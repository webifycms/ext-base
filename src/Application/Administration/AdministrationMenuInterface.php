<?php
declare(strict_types=1);

namespace OneCMS\Base\Application\Administration;

/**
 * Interface AdministrationMenuInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface AdministrationMenuInterface
{
    /**
     * @param array $items
     */
    public function addItems(array $items): void;
}