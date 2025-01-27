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
use Webify\Base\Infrastructure\Service\Administration\AdministrationService;
use yii\web\Application;

/**
 * Web application service that is contains the web application instance.
 */
final class WebApplicationService implements WebApplicationServiceInterface
{
	private readonly Application $application;

	private readonly string $administrationPath;

	/**
	 * Application constructor.
	 */
	public function __construct(
		private readonly DependencyServiceInterface $dependencyService,
		private readonly ConfigServiceInterface $configService
	) {
		$this->administrationPath = $configService->getConfig(
			'administrationPath',
			self::DEFAULT_ADMINISTRATION_PATH
		);
		$adminService = new AdministrationService($this->administrationPath);

		// let's register the high level services to container
		// @phpstan-ignore-next-line
		$this->dependencyService->getContainer()->setDefinitions(
			[
				WebApplicationServiceInterface::class    => fn () => $this,
				ConfigServiceInterface::class            => fn () => $this->configService,
				AdministrationServiceInterface::class    => fn () => $adminService,
			]
		);

		// initialize framework web application
		try {
			$this->application = new Application(
				$this->configService->getConfig('framework')
			);
		} catch (\Throwable $throwable) {
			throw new TranslatableRuntimeException(
				'unable_to_init_app',
				[],
				$throwable->getCode(),
				$throwable
			);
		}

		$this->bootstrap();
	}

	public function bootstrap(): void
	{
		$classes = $this->configService->getConfig('bootstrap', null);

		// let's initialize the bootstrap classes
		if (!empty($classes)) {
			foreach ($classes as $class) {
				/**
				 * @var BootstrapServiceInterface $object
				 */
				$object = new $class($this->configService, $this);

				$object->init();
			}
		}

		$this->application->run();
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
		// @phpstan-ignore-next-line
		return $this->dependencyService->getContainer()->get($name, $params, $config);
	}
}
