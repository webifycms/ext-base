<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Application;

use RuntimeException;
use Throwable;
use OneCMS\Base\Domain\Service\Administration\AdministrationServiceInterface;
use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\web\Application;

/**
 * Class Application
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class WebApplicationService implements ApplicationServiceInterface, WebApplicationServiceInterface
{
    /**
     * @var Application
     */
    private Application $application;
    /**
     * @var AdministrationServiceInterface|null
     */
    private ?AdministrationServiceInterface $administration = null;
    /**
     * @var string
     */
    private string $administrationPath = 'administration';

    /**
     * Application constructor.
     */
    public function __construct(
        private readonly DependencyServiceInterface $dependency,
        private readonly array $config
    ) {
        $this->administrationPath = $config['administrationPath'] ?? $this->administrationPath;

        $this->createApplication();
    }

    /**
     * Create the framework application.
     */
    private function createApplication()
    {
        $config = $this->config['framework'] ?? ['id' => 'web'];

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
    public function inAdministration(): bool
    {
        return $this->administration instanceof AdministrationServiceInterface;
    }

    /**
     * @inheritDoc
     */
    public function getAdministrationPath(): string
    {
        return $this->administrationPath;
    }

    /**
     * @inheritDoc
     */
    public function getAdministration(): AdministrationServiceInterface
    {
        return $this->getService(AdministrationServiceInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function getService(string $name, array $params = [], array $config = [])
    {
        return $this->dependency->getContainer()->get($name, $params, $config);
    }
}
