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
    /**
     * @return DependencyInterface
     */
    public function getDependency(): DependencyInterface;

    /**
     * @param string $name
     * @param array $params
     * @param array $config
     *
     * @return mixed
     */
    public function getService(string $name, array $params, array $config);

    /**
     * @return Application
     */
    public function getComponent(): Application;

    /**
     * @return bool
     */
    public function inAdministration(): bool;

    /**
     * @return AdministrationInterface
     */
    public function getAdministration(): AdministrationInterface;
}