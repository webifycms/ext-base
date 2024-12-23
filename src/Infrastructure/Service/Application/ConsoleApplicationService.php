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

use JetBrains\PhpStorm\NoReturn;
use Webify\Base\Domain\Exception\TranslatableRuntimeException;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\console\Application;

use function Webify\Base\Infrastructure\log_message;

/**
 * Console application service that is contains the console application instance.
 */
final class ConsoleApplicationService implements ConsoleApplicationServiceInterface
{
	private const DEFAULT_CONFIGURATIONS = ['id' => 'console'];

	private readonly Application $application;

	/**
	 * Application constructor.
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		ConfigServiceInterface $config
	) {
		// initialize framework console application
		try {
			// Register the configurations to the container,
			// so where ever we need configurations we can use the ConfigServiceInterface.
			$this->dependencyService->getContainer()->setDefinitions([
				ConsoleApplicationServiceInterface::class => fn () => $this,
				ConfigServiceInterface::class             => fn () => $config,
			]);

			$this->application = new Application($config->getConfig('framework', self::DEFAULT_CONFIGURATIONS));
		} catch (\Throwable $throwable) {
			log_message('debug', [
				'message' => $throwable->getMessage(),
				'trace'   => $throwable->getTraceAsString(),
			]);

			throw new TranslatableRuntimeException('unable_to_initiate_app');
		}
	}

	#[NoReturn]
	public function bootstrap(): void
	{
		$classes = $this->getConfig('bootstrap', null);

		// initiate & run the bootstrap classes
		if (!empty($classes)) {
			foreach ($classes as $class) {
				(new $class($this->dependencyService, $this))->init();
			}
		}

		$output = $this->application->run();

		exit($output);
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

		throw new TranslatableRuntimeException('property_not_exist', ['property' => $name]);
	}

	public function setApplicationProperty(string $name, mixed $value): void
	{
		if ($this->application->canSetProperty($name)) {
			$this->application->{$name} = $value;
		}

		$this->application->params[$name] = $value;
	}

	public function getService(string $name, array $params = [], array $config = []): mixed
	{
		return $this->dependencyService->getContainer()->get($name, $params, $config);
	}
}
