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
    private Container $container;

    /**
     * DependencyContainer constructor.
     */
    public function __construct()
    {
        $this->container = Yii::$container;

        $this->container->setSingleton(DependencyInterface::class, $this);
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param string $class
     * @param array $params
     * @param array $config
     *
     * @return object|string
     */
    public function get(string $class, array $params = [], array $config = [])
    {
        try {
            return $this->container->get($class, $params, $config);
        } catch (Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage());
        }
    }
}