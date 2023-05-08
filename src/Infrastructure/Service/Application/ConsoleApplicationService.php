<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service\Application;

use Webify\Base\Domain\Exception\TranslatableRuntimeException;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface as DomainApplicationServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\console\Application;

use function Webify\Base\Infrastructure\log_message;

/**
 * Console application service that is contains the console application instance.
 */
final class ConsoleApplicationService implements DomainApplicationServiceInterface, ApplicationServiceInterface, ConsoleApplicationServiceInterface
{
	private Application $application;

	/**
	 * Application constructor.
	 *
	 * @param array<string, mixed> $config
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependency,
		private readonly array $config
	) {
		$this->initiateApplication();
	}

	/**
	 * {@inheritDoc}
	 */
	public function start(): void
	{
		$this->application->run();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfig(): array
	{
		return $this->config;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDependency(): DependencyServiceInterface
	{
		return $this->dependency;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getApplication(): Application
	{
		return $this->application;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws TranslatableRuntimeException if property not exist or set
	 */
	public function getApplicationProperty(string $name): mixed
	{
		if ($this->application->canGetProperty($name)) {
			return $this->application->{$name};
		}

		if (isset($this->application['params'][$name])) {
			return $this->application['params'][$name];
		}

		throw new TranslatableRuntimeException('property_not_exist', [
			'property' => $name,
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setApplicationProperty(string $name, mixed $value): void
	{
		if ($this->application->canSetProperty($name)) {
			$this->application->{$name} = $value;
		}

		$this->application->params[$name] = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getService(string $name, array $params = [], array $config = []): mixed
	{
		return $this->dependency->getContainer()->get($name, $params, $config);
	}

	/**
	 * Initiates the framework application.
	 */
	private function initiateApplication(): void
	{
		$config = $this->config['framework'] ?? ['id' => 'console'];

		try {
			$this->application = new Application($config);
		} catch (\Throwable $throwable) {
			log_message('debug', [
				'message' => $throwable->getMessage(),
				'trace'   => $throwable->getTraceAsString(),
			]);

			throw new TranslatableRuntimeException('unable_to_initiate_app');
		}
	}
}
