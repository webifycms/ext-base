<?php

namespace OneCMS\Base;

use OneCMS\Base\Domain\Service\Uuid\UuidServiceInterface as UuidUuidServiceInterface;
use OneCMS\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use OneCMS\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;
use OneCMS\Base\Infrastructure\Service\Uuid\UuidService;

/**
 * WebBootstrap
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class WebBootstrap extends WebBootstrapService implements RegisterDependencyBootstrapInterface
{
    /**
     * @inheritDoc
     */
    public function dependencies(): array
    {
        return [
            UuidUuidServiceInterface::class => UuidService::class
        ];
    }
}
