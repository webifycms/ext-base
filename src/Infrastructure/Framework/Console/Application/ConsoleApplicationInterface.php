<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Console\Application;

use OneCMS\Base\Application\ApplicationInterface;
use OneCMS\Base\Infrastructure\Framework\Dependency\DependencyInterface;
use yii\console\Application;

/**
 * Interface ConsoleApplicationInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface ConsoleApplicationInterface extends ApplicationInterface
{
    public function getDependency(): DependencyInterface;

    /**
     *
     * @return mixed
     */
    public function getService(string $name, array $params, array $config);

    public function getComponent(): Application;
}