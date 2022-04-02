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
    public function getPath(): string;

    /**
     * @return string returns absolute url.
     */
    public function getUrl(): string;

    public function getMenu(): AdministrationMenuInterface;

    public function inAdministration(): bool;
}
