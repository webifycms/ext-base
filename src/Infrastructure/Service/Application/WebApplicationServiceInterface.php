<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Application;

use OneCMS\Base\Domain\Service\Administration\AdministrationServiceInterface;
use yii\web\Application;

/**
 * Interface WebApplicationServiceInterface
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface WebApplicationServiceInterface extends ApplicationServiceInterface
{
    /**
     * Returns the web application instance.
     *
     * @return Application
     */
    public function getApplication(): Application;

    /**
     * @return boolean returns true if in administration otherwise returns false.
     */
    public function inAdministration(): bool;

    /**
     * Returns administration service instance.
     *
     * @return AdministrationServiceInterface
     */
    public function getAdministration(): AdministrationServiceInterface;
}
