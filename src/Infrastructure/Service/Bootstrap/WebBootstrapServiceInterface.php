<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\web\Application;

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

    /**
     * Returns the web application instance.
     *
     * @return Application
     */
    public function getApplication(): Application;
}
