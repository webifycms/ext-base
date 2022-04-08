<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Dependency;

use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Yii;
use yii\di\Container;

/**
 * DependencyService
 * 
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class DependencyService extends DependencyServiceInterface
{
    /**
     * @var Container
     */
    private readonly Container $container;

    /**
     * DependencyContainer constructor.
     */
    public function __construct()
    {
        // gets the framework container
        $this->container = Yii::$container;
        // the class itself registers to the container
        $this->container->setSingleton(DependencyInterface::class, $this);
    }

    /**
     * @inheritDoc
     *
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
}
