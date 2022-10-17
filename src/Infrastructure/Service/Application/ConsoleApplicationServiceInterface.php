<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Application;

use yii\console\Application;

/**
 * ConsoleApplicationServiceInterface.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
interface ConsoleApplicationServiceInterface
{
	/**
	 * Returns the console application instance.
	 */
	public function getApplication(): Application;
}
