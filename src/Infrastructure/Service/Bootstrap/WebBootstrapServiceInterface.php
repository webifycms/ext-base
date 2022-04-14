<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

/**
 * WebBootstrapServiceInterface
 */
interface WebBootstrapServiceInterface
{
    /**
     * Returns the web application service instance.
     *
     * @return WebApplicationServiceInterface|ApplicationServiceInterface
     */
    public function getApplicationService(): WebApplicationServiceInterface|ApplicationServiceInterface;
}
