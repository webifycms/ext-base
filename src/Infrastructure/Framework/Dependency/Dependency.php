<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Dependency;


use RuntimeException;
use Throwable;
use Yii;
use yii\di\Container;

/**
 * Class DependencyContainer
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Dependency implements DependencyInterface
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
        $this->container = Yii::$container;

        $this->container->setSingleton(DependencyInterface::class, $this);
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     *
     * @return object|string
     */
    public function get(string $class, array $params = [], array $config = []): object|string
    {
        try {
            return $this->container->get($class, $params, $config);
        } catch (Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage());
        }
    }
}