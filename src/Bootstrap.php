<?php

namespace OneCMS\Base;

use OneCMS\Base\Application\Administration\AdministrationInterface;
use OneCMS\Base\Domain\Service\UuidServiceInterface;
use OneCMS\Base\Infrastructure\Framework\Bootstrap\RegisterDependencyBootstrapInterface;
use OneCMS\Base\Infrastructure\Framework\Web\Administration\Administration;
use OneCMS\Base\Infrastructure\Library\Uuid\UuidService;
use OneCMS\Base\Infrastructure\Framework\Bootstrap\AbstractBootstrap;

/**
 * Class Bootstrap
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Bootstrap extends AbstractBootstrap implements RegisterDependencyBootstrapInterface
{
    /**
     * @inheritDoc
     */
    public function dependencies(): array
    {
        return [
            AdministrationInterface::class => Administration::class,
            UuidServiceInterface::class => UuidService::class,
        ];
    }
}