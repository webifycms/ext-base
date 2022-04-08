<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Administration;

/**
 * AdministrationServiceInterface
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface AdministrationServiceInterface
{
    /**
     * Returns the administration path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Returns absolute url of the administration.
     * 
     * @return string
     */
    public function getUrl(): string;

    /**
     * Set adminstration menu items.
     *
     * @param array $items
     */
    public function setMenuItems(array $items): void;

    /**
     * Returns true if in administration, otherwise returns false.
     *
     * @return boolean
     */
    public function inAdministration(): bool;
}
