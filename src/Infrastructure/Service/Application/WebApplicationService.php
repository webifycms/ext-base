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
use Webify\Base\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Component\Application\WebApplicationComponent;
use Webify\Base\Infrastructure\Service\Administration\AdministrationService;
use yii\base\InvalidConfigException;
use yii\di\Container;

/**
 * The WebApplicationService is responsible for setting up and managing the lifecycle
 * of the web application.
 *
 * It initialises dependencies, configuration, and bootstrap
 * services required for the application to function properly.
 */
final class WebApplicationService implements WebApplicationServiceInterface
{
	use InitialiseExtensionsApplicationServiceTrait;

	private WebApplicationComponent $application;

	private readonly string $administrationPath;

	/**
	 * @var array<string, ExtensionInterface>
	 */
	private array $extensions = [];

	private readonly Container $container;

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
		$this->administrationPath       = $configService->getConfig(
			'administrationPath',
			self::DEFAULT_ADMINISTRATION_PATH
		);

		// let's register the high-level services to the container
		$this->container->setSingletons(
			[
				ConfigServiceInterface::class         => fn () => $this->configService,
				WebApplicationServiceInterface::class => fn () => $this,
				AdministrationServiceInterface::class => fn () => new AdministrationService($this->administrationPath),
			]
		);

		// let's initialise the extensions
		$this->initialiseExtensions($this->configService->getConfig('extensions', []));
	}

	public function run(): void
	{
		// initialise framework web application
		$this->application = new WebApplicationComponent(
			$this->configService->getConfig('framework')
		);

		$this->postExtensionsInitialisation($this->extensions);
		$this->application->run();
	}

	public function getConfig(?string $key, mixed $default): mixed
	{
		return $this->configService->getConfig($key, $default);
	}

	public function getApplication(): WebApplicationComponent
	{
		return $this->application;
	}

	/**
	 * @throws TranslatableRuntimeException if property doesn't exist
	 */
	public function getApplicationProperty(string $name): mixed
	{
		if ($this->application->canGetProperty($name)) {
			return $this->application->{$name};
		}

		if (isset($this->application->params[$name])) {
			return $this->application->params[$name];
		}

		throw new TranslatableRuntimeException(
			'property_not_exist',
			[
				'property' => $name,
			]
		);
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
