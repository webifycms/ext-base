<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Web\Application;

use OneCMS\Base\Application\Administration\AdministrationInterface;
use OneCMS\Base\Application\Config\ConfigInterface;
use OneCMS\Base\Infrastructure\Framework\Dependency\DependencyInterface;
use RuntimeException;
use Throwable;
use yii\web\Application as Application;

/**
 * Class Application
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class WebApplication implements WebApplicationInterface
{
    /**
     * @var Application
     */
    private Application $component;
    /**
     * @var AdministrationInterface|null
     */
    private ?AdministrationInterface $administration = null;
    /**
     * @var string
     */
    private string $administrationPath = 'administration';

    /**
     * Application constructor.
     */
    public function __construct(private readonly DependencyInterface $dependency, private readonly ConfigInterface $config)
    {
        $this->administrationPath = $config->get('administrationPath') ?? $this->administrationPath;

        $this->createApplication();
    }

    /**
     * Create the framework application.
     */
    private function createApplication()
    {
        $config = $this->config->get('framework');

        if (empty($config)) {
            throw new RuntimeException("The web application configurations were not defined.");
        }

        try {
            $this->component = new Application($config);
        } catch (Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function bootstrap()
    {
        $this->component->run();
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getDependency(): DependencyInterface
    {
        return $this->dependency;
    }

    /**
     * Returns the framework component.
     */
    public function getComponent(): Application
    {
        return $this->component;
    }

    /**
     * Get the information for the given name if defined.
     *
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
     * @param mixed $value
     */
    public function set(string $name, $value): void
    {
        if ($this->component->canSetProperty($name)) {
            $this->component->$name = $value;
        }

        $this->component->params[$name] = $value;
    }

    public function inAdministration(): bool
    {
        return $this->administration instanceof AdministrationInterface;
    }

    public function getAdministration(): AdministrationInterface
    {
        return $this->dependency->get(AdministrationInterface::class);
    }

    /**
     * Returns the service instance for the given name that registered in the container.
     *
     * @param string $name Alias or fully qualified class name that has registered with the container.
     * @param array $params Array of constructor parameters values.
     * @param array $config Array of name-value pairs that will be used to initialize the object properties.
     * @return mixed|object|null
     */
    public function getService(string $name, array $params = [], array $config = [])
    {
        return $this->dependency->get($name, $params, $config);
    }
}