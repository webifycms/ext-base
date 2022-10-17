<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Bootstrap;

/**
 * Interface RegisterDependencyBootstrapInterface.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
interface RegisterDependencyBootstrapInterface
{
	/**
	 * Define dependencies.
	 *
	 * @return array<mixed>
	 */
	public function dependencies(): array;
}
