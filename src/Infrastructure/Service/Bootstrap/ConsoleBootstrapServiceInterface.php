<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

use OneCMS\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;

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
