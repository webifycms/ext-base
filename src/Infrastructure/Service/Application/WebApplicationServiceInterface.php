<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Application;

use OneCMS\Base\Domain\Service\Administration\AdministrationServiceInterface;
use yii\web\Application;

/**
 * Interface WebApplicationServiceInterface.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
interface WebApplicationServiceInterface
{
	/**
	 * Returns the web application instance.
	 */
	public function getApplication(): Application;

	/**
	 * @return bool returns true if in administration otherwise returns false
	 */
	public function inAdministration(): bool;

	/**
	 * Returns the administration path.
	 */
	public function getAdministrationPath(): string;

	/**
	 * Returns administration service instance.
	 */
	public function getAdministration(): AdministrationServiceInterface;
}
