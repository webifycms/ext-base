<?php
declare(strict_types=1);

namespace OneCMS\Base\Application\Administration;

/**
 * Interface AdministrationInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface AdministrationInterface
{
    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string returns absolute url.
     */
    public function getUrl(): string;

    /**
     * @return AdministrationMenuInterface
     */
    public function getMenu(): AdministrationMenuInterface;

    /**
     * @return bool
     */
    public function inAdministration(): bool;
}
