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
use Webify\Base\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Domain\Service\Bootstrap\BootstrapServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Component\Application\WebApplicationComponent;
use Webify\Base\Infrastructure\Service\Administration\AdministrationService;
use Webify\Base\Infrastructure\Service\Bootstrap\WebBootstrapServiceInterface;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

/**
 * Web application service that is contains the web application instance.
 */
final class WebApplicationService implements WebApplicationServiceInterface
{
	private WebApplicationComponent $application;

	private readonly string $administrationPath;

	/**
	 * @var array<BootstrapServiceInterface>
	 */
	private array $bootstrap = [];

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

		// let's register the high level services to the container
		$this->container->setSingletons(
			[
				ConfigServiceInterface::class         => fn () => $this->configService,
				WebApplicationServiceInterface::class => fn () => $this,
				AdministrationServiceInterface::class => fn () => new AdministrationService($this->administrationPath),
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
		// initialize framework web application
		$this->application = new WebApplicationComponent(
			$this->configService->getConfig('framework')
		);

		if (!empty($this->bootstrap)) {
			foreach ($this->bootstrap as $object) {
				if ($object instanceof WebBootstrapServiceInterface) {
					$object->bootstrap($this);
				}
			}
		}

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
	 * @throws TranslatableRuntimeException if property not exist
	 */
	public function getApplicationProperty(string $name): mixed
	{
		if ($this->application->canGetProperty($name)) {
			return $this->application->{$name};
		}

		if (isset($this->application->params[$name])) {
			return $this->application->params[$name];
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
		try {
			return $this->container->get($name, $params, $config);
		} catch (InvalidConfigException|NotInstantiableException $e) {
			throw new TranslatableRuntimeException(
				'service_not_exist',
				['service' => $name],
				$e->getCode(),
				$e
			);
		}
	}
}
