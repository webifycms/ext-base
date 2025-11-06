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
use Webify\Base\Domain\ExtensionInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\base\InvalidConfigException;
use yii\console\Application;
use yii\di\Container;

/**
 * Console application service that contains the console application instance.
 */
final class ConsoleApplicationService implements ConsoleApplicationServiceInterface
{
	use InitialiseExtensionsApplicationServiceTrait;

	private Application $application;

	private readonly Container $container;

	/**
	 * @var array<string, ExtensionInterface>
	 */
	private array $extensions = [];

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

		// let's register the high-level services to the container
		$this->container->setSingletons(
			[
				ConfigServiceInterface::class             => fn () => $this->configService,
				ConsoleApplicationServiceInterface::class => fn () => $this,
			]
		);

		// let's initialise the extensions
		$this->initialiseExtensions($this->configService->getConfig('extensions', []));
	}

	/**
	 * @throws InvalidConfigException
	 */
	public function run(): void
	{
		// initialise framework console application
		$this->application = new Application(
			$this->configService->getConfig('framework')
		);

		$this->postExtensionsInitialisation($this->extensions);

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
	 * @throws TranslatableRuntimeException if property doesn't exist or set
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

	/**
	 * @inheritDoc
	 *
	 * @return object
	 */
	public function getService(string $name, array $params = [], array $config = []): object
	{
		try {
			return $this->container->get($name, $params, $config);
		} catch (InvalidConfigException $e) {
			throw new TranslatableRuntimeException(
				'service_not_exist',
				['service' => $name],
				$e->getCode(),
				$e
			);
		}
	}

	public function getExtension(string $name): ExtensionInterface
	{
		return $this->extensions[$name];
	}

	/**
	 * @return array<string, ExtensionInterface>
	 */
	public function getExtensions(): array
	{
		return $this->extensions;
	}
}
