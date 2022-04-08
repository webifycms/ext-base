<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Application;

use yii\console\Application;

/**
 * ConsoleApplicationServiceInterface
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface ConsoleApplicationServiceInterface extends ApplicationServiceInterface
{
    /**
     * Returns the console application instance.
     *
     * @return Application
     */
    public function getApplication(): Application;
}
