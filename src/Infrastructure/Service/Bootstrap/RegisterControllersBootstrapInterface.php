<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

/**
 * Interface RegisterControllersBootstrapInterface.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
interface RegisterControllersBootstrapInterface
{
	public function controllers(): array;
}
