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
use Webify\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\ConsoleBootstrapServiceInterface;
use yii\base\InvalidConfigException;
use yii\console\Application;
use yii\di\Container;

/**
 * Console application service that is contains the console application instance.
 */
final class ConsoleApplicationService implements ConsoleApplicationServiceInterface
{
	private Application $application;

	private readonly Container $container;

	/**
	 * @var array<BootstrapServiceInterface>
	 */
	private array $bootstrap = [];

	/**
	 * Application constructor.
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		private readonly ConfigServiceInterface $configService
	) {
		/**
		 * @var Container $container
		 */
		$container                      = $this->dependencyService->getContainer();
		$this->container                = $container;

		// let's register the high level services to the container
		$this->container->setSingletons(
			[
				ConfigServiceInterface::class             => fn () => $this->configService,
				ConsoleApplicationServiceInterface::class => fn () => $this,
			]
		);

		$bootstrapClasses = $this->configService->getConfig('bootstrap', []);

		// let's initialize the bootstrap classes
		if (!empty($bootstrapClasses)) {
			foreach ($bootstrapClasses as $class) {
				/**
				 * @var BootstrapServiceInterface $class
				 */
				$class             = new $class($this->dependencyService, $this->configService);
				$this->bootstrap[] = $class;
			}
		}
	}

	/**
	 * @throws InvalidConfigException
	 */
	public function run(): void
	{
		$this->application = new Application(
			$this->configService->getConfig('framework')
		);

		if (!empty($this->bootstrap)) {
			foreach ($this->bootstrap as $object) {
				if ($object instanceof ConsoleBootstrapServiceInterface) {
					$object->bootstrap($this);
				}
			}
		}

		$output = $this->application->run();

		exit($output);
	}

	public function getConfig(?string $key, mixed $default): mixed
	{
		return $this->configService->getConfig($key, $default);
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

		if (isset($this->application->params[$name])) {
			return $this->application->params[$name];
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
		// @phpstan-ignore-next-line
		return $this->dependencyService->getContainer()->get($name, $params, $config);
	}
}
