<?php

declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service\Bootstrap;

use Webify\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\web\Application;

/**
 * WebBootstrapServiceInterface.
 */
interface WebBootstrapServiceInterface
{
	/**
	 * Returns the web application service instance.
	 */
	public function getApplicationService(): WebApplicationServiceInterface|ApplicationServiceInterface;

	/**
	 * Returns the web application instance.
	 */
	public function getApplication(): Application;
}
