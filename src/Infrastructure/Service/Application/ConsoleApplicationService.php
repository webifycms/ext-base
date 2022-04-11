<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Application;

use RuntimeException;
use Throwable;
use OneCMS\Base\Application\Config\ConfigInterface;
use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\console\Application;

/**
 * ConsoleApplicationService
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class ConsoleApplicationService implements ConsoleApplicationServiceInterface
{
    /**
     * @var Application
     */
    private Application $application;

    /**
     * Application constructor.
     */
    public function __construct(
        private readonly DependencyServiceInterface $dependency,
        private readonly array $config
    ) {
        $this->createApplication();
    }

    /**
     * Create the framework application.
     */
    private function createApplication()
    {
        $config = $config['framework'] ?? [];

        try {
            $this->application = new Application($config);
        } catch (Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function start()
    {
        $this->application->run();
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function getDependency(): DependencyServiceInterface
    {
        return $this->dependency;
    }

    /**
     * @inheritDoc
     */
    public function getApplication(): Application
    {
        return $this->application;
    }

    /**
     * @inheritDoc
     * 
     * @throws RuntimeException if property not exist or set.
     */
    public function getApplicaitonProperty(string $name): mixed
    {
        if ($this->application->canGetProperty($name)) {
            return $this->application->$name;
        }

        if (isset($this->application['params'][$name])) {
            return $this->application['params'][$name];
        }

        throw new RuntimeException('The information "' . $name . '" not defined.');
    }

    /**
     * @inheritDoc
     */
    public function setApplicaitonProperty(string $name, mixed $value): void
    {
        if ($this->application->canSetProperty($name)) {
            $this->application->$name = $value;
        }

        $this->application->params[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function getService(string $name, array $params = [], array $config = [])
    {
        return $this->dependency->getContainer()->get($name, $params, $config);
    }
}
