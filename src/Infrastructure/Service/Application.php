<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service;

use Psr\Container\ContainerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Container\ContainerBuilderInterface;
use Webify\Base\Infrastructure\Contract\{BootstrapServiceProviderInterface, ServiceProviderInterface};
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Exception\ApplicationException;

/**
 * The application class.
 * Bootstrap — uses configurations and environment and wires container, and kernels together.
 *
 * Usage:
 *
 * ```php
 * $app = new Application($config, $environment);
 *
 * $app->registerProviders(new ServiceProvider1(), new ServiceProvider2());
 * $app->bootstrap(new ContainerBuilder());
 * $app->run();
 * ```
 */
final class Application
{
	/**
	 * @var ContainerInterface the application container
	 */
	private ContainerInterface $container;

	/**
	 * @var array<BootstrapServiceProviderInterface|ServiceProviderInterface> the application service providers
	 */
	private array $providers = [];

	/**
	 * The constructor.
	 *
	 * @param ConfigInterface $config      the configuration instance
	 * @param Environment     $environment the environment instance
	 *
	 * @throws ApplicationException if basePath or runtimePath is not defined or runtimePath is not writable
	 */
	public function __construct(
		private readonly ConfigInterface $config,
		private readonly Environment $environment
	) {
		if ('' === $this->config->basePath) {
			throw ApplicationException::basePathNotDefined();
		}

		$runtimePath = $this->config->runtimePath;

		if ('' === $runtimePath) {
			throw ApplicationException::runtimePathNotDefined();
		}

		if (!is_writable($runtimePath)) {
			throw ApplicationException::runtimePathIsNotWritable($runtimePath);
		}

		$this->createRuntimeDirectories();
	}

	/**
	 * Register service providers and this should be called before booting the application.
	 */
	public function registerProvider(BootstrapServiceProviderInterface|ServiceProviderInterface $provider): void
	{
		$this->providers[] = $provider;
	}

	/**
	 * Get the application container.
	 */
	public function getContainer(): ContainerInterface
	{
		return $this->container;
	}

	/**
	 * Get the application service providers.
	 *
	 * @return array<BootstrapServiceProviderInterface|ServiceProviderInterface>
	 */
	public function getProviders(): array
	{
		return $this->providers;
	}

	/**
	 * Get the application configuration.
	 */
	public function getConfig(): ConfigInterface
	{
		return $this->config;
	}

	/**
	 * Get the application environment.
	 */
	public function getEnvironment(): Environment
	{
		return $this->environment;
	}

	/**
	 * Bootstrap the application and handle an HTTP request.
	 */
	public function run(): void
	{
		$this->container->get('httpKernel')->handle();
	}

	/**
	 * Bootstrap the application and handle a console command.
	 */
	public function runConsole(): never
	{
		exit($this->container->get('consoleKernel')->handle());
	}

	/**
	 * Bootstrap the application.
	 */
	public function bootstrap(ContainerBuilderInterface $containerBuilder): void
	{
		$this->container = $containerBuilder->build($this);

		// Bootstrap all service providers that implement `BootstrapServiceProviderInterface`
		foreach ($this->providers as $provider) {
			if ($provider instanceof BootstrapServiceProviderInterface) {
				$provider->bootstrap($this->container);
			}
		}
	}

	/**
	 * Create runtime directories if they don't exist.
	 *
	 * @throws ApplicationException if unable to create runtime directories
	 */
	private function createRuntimeDirectories(): void
	{
		$cachePath = $this->config->cachePath;

		if (!is_dir($cachePath) && !mkdir($cachePath, 0o755, true)) {
			throw ApplicationException::unableToCreateRuntimePaths();
		}

		$logPath = $this->config->logPath;

		if (!is_dir($logPath) && !mkdir($logPath, 0o755, true)) {
			throw ApplicationException::unableToCreateRuntimePaths();
		}
	}
}
