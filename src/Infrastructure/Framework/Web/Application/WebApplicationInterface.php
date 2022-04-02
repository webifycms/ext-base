<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Web\Application;

use OneCMS\Base\Application\ApplicationInterface;
use OneCMS\Base\Application\Administration\AdministrationInterface;
use OneCMS\Base\Infrastructure\Framework\Dependency\DependencyInterface;
use yii\web\Application;

/**
 * Interface WebApplicationInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface WebApplicationInterface extends ApplicationInterface
{
    public function getDependency(): DependencyInterface;

    /**
     *
     * @return mixed
     */
    public function getService(string $name, array $params, array $config);

    public function getComponent(): Application;

    public function inAdministration(): bool;

    public function getAdministration(): AdministrationInterface;
}