<?php

declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service\Bootstrap;

use Webify\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;

/**
 * ConsoleBootstrapServiceInterface.
 */
interface ConsoleBootstrapServiceInterface
{
	/**
	 * Returns the console application service instance.
	 */
	public function getApplicationService(): ApplicationServiceInterface|ConsoleApplicationServiceInterface;
}
