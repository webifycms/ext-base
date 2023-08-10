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
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\web\Application;

use function Webify\Base\Infrastructure\log_message;

/**
 * Web application service that is contains the web application instance.
 */
final class WebApplicationService implements DomainApplicationServiceInterface, ApplicationServiceInterface, WebApplicationServiceInterface
{
	private const DEFAULT_CONFIGURATIONS = ['id' => 'web'];

	private readonly Application $application;

	private readonly string $administrationPath;

	/**
	 * Application constructor.
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		ConfigServiceInterface $config
	) {
		$this->administrationPath = $config->getConfig('administrationPath', self::DEFAULT_ADMINISTRATION_PATH);

		// initialize framework web application
		try {
			$this->application = new Application($config->getConfig('framework', self::DEFAULT_CONFIGURATIONS));
		} catch (\Throwable $throwable) {
			log_message('debug', [
				'message' => $throwable->getMessage(),
				'trace'   => $throwable->getTraceAsString(),
			]);

			throw new TranslatableRuntimeException('unable_to_initiate_app');
		}

		// let's register the application service and the configurations to the container
		$this->dependencyService->getContainer()->setDefinitions([
			DomainApplicationServiceInterface::class => fn () => $this,
			ConfigServiceInterface::class            => fn () => $config,
		]);
	}

	public function bootstrap(): void
	{
		$classes = $this->getConfig('bootstrap', null);

		// if have bootstrap classes, initiate & run them
		if (!empty($classes)) {
			foreach ($classes as $class) {
				(new $class($this->dependencyService, $this))->init();
			}
		}

		$this->application->run();
	}

	public function getConfig(?string $key, mixed $default): mixed
	{
		/**
		 * @var ConfigServiceInterface $config
		 */
		$config = $this->getService(ConfigServiceInterface::class);

		return $config->getConfig($key, $default);
	}

	public function getDependency(): DependencyServiceInterface
	{
		return $this->dependencyService;
	}

	public function getApplication(): Application
	{
		return $this->application;
	}

	/**
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

	public function setApplicationProperty(string $name, mixed $value): void
	{
		if ($this->application->canSetProperty($name)) {
			$this->application->{$name} = $value;
		}

		$this->application->params[$name] = $value;
	}

	public function getAdministrationPath(): string
	{
		return $this->administrationPath;
	}

	public function getService(string $name, array $params = [], array $config = []): mixed
	{
		return $this->dependencyService->getContainer()->get($name, $params, $config);
	}
}
