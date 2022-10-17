<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Bootstrap;

use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;

/**
 * BootstrapServiceInterface.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
interface BootstrapServiceInterface
{
	/**
	 * Initialize the bootstrap service.
	 */
	public function init(): void;

	/**
	 * Returns the dependecny service instance.
	 */
	public function getDependencyService(): DependencyServiceInterface;
}
