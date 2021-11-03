<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Application;


use OneCMS\Base\Application\Administration\AdministrationInterface;
use OneCMS\Base\Application\Config\ConfigInterface;
use OneCMS\Base\Infrastructure\Framework\Dependency\DependencyInterface;
use RuntimeException;
use Yii;

/**
 * Class Application
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
abstract class AbstractApplication
{
    /**
     * @var DependencyInterface
     */
    protected DependencyInterface $dependencyManager;
    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;
    /**
     * @var mixed The framework application component.
     */
    protected $component;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        if (!$this->dependencyManager) {
            throw new RuntimeException('"' . get_class($this)
                . '::dm" should defined on inherited classes.');
        }

        if (!$this->config) {
            throw new RuntimeException('"' . get_class($this)
                . '::config" should defined on inherited classes.');
        }

        if (!$this->component) {
            throw new RuntimeException('"' . get_class($this)
                . '::component" should defined on inherited classes.');
        }
    }

    /**
     * @return ConfigInterface
     */
    public function config(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * @return DependencyInterface
     */
    public function dependencyManager(): DependencyInterface
    {
        return $this->dependencyManager;
    }

    /**
     * Returns the framework component.
     *
     * @return mixed
     */
    public function component()
    {
        return $this->component;
    }

    /**
     * Get the information for the given name if defined.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name)
    {
        if ($this->component->canGetProperty($name)) {
            return $this->component->$name;
        }

        if (isset($this->component['params'][$name])) {
            return $this->component['params'][$name];
        }

        throw new RuntimeException('The information "' . $name . '" not defined.');
    }

    /**
     * Define information.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value): void
    {
        if ($this->component->canSetProperty($name)) {
            $this->component->$name = $value;
        }

        $this->component->params[$name] = $value;
    }

    /**
     * @return AdministrationInterface|object|string
     */
    public function administration(): AdministrationInterface
    {
        if ($this instanceof ConsoleApplication) {
            throw new RuntimeException('Administration is not accessible on console application.');
        }

        return $this->service(AdministrationInterface::class);
    }

    /**
     * Returns the service instance for the given name if registered in the container.
     *
     * @param string $name Alias or fully qualified class name that has registered with the container.
     * @param array $params Array of constructor parameters values.
     * @param array $config Array of name-value pairs that will be used to initialize the object properties.
     *
     * @return mixed
     */
    public function service(string $name, array $params = [], array $config = [])
    {
        if (!is_null($component = $this->component->get($name, false))) {
            return $component;
        }

        return $this->dependencyManager->get($name, $params, $config);
    }

//    /**
//     * @param string $name
//     * @param array  $params
//     *
//     * @return mixed
//     */
//    public function __call(string $name, array $params = [])
//    {
//        if ($this->component->hasMethod($name)) {
//            return $this->component->$name(...$params);
//        }
//
//        throw new UnknownMethodException('The method "'
//            . get_class($this->component) . '::' . $name . '" not exist.');
//    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setAlias(string $name, string $value): void
    {
        Yii::setAlias($name, $value);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getAlias(string $name): ?string
    {
        $alias = Yii::getAlias($name, false);

        return !$alias ? null : $alias;
    }

    /**
     * @return mixed
     */
    abstract protected function createApplication();
}