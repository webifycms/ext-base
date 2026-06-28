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

namespace Webify\Base\Infrastructure\Container;

use DI\{Container, ContainerBuilder};
use Psr\Container\ContainerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\{BootstrapServiceProviderInterface, ExtensionInterface, ServiceProviderInterface};
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Service\Application;

/**
 * Factory for building a PHP-Di container.
 */
final class PhpDiContainerBuilder implements ContainerBuilderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function build(Application $app): ContainerInterface
	{
		$builder = new ContainerBuilder();

		$builder->useAutowiring(true);

		if ($app->getEnvironment()->isProduction()) {
			$builder->enableCompilation($app->getConfig()->cachePath);
			$builder->writeProxiesToFile(true, $app->getConfig()->cachePath);
		}

		// Config is made available before any provider definitions are added,
		// so providers can reference it during the build phase if needed.
		$builder->addDefinitions([ConfigInterface::class => $app->getConfig()]);
		$builder->addDefinitions([Environment::class => $app->getEnvironment()]);
		$this->addProvidersDefinition($app, $builder);
		$this->addExtensionProvidersDefinition($app, $builder);

		return $builder->build();
	}

	/**
	 * Adds provider definitions to the container builder.
	 *
	 * @param ContainerBuilder<Container> $builder
	 */
	private function addProvidersDefinition(Application $app, ContainerBuilder $builder): void
	{
		/** @var array<string> $providerList */
		$providerList = $app->getConfig()->get('providers', []);

		foreach ($providerList as $provider) {
			/** @var BootstrapServiceProviderInterface|ServiceProviderInterface $provider */
			$provider = new $provider();

			$app->registerProvider($provider);

			if ($provider instanceof ServiceProviderInterface) {
				$builder->addDefinitions($provider->getDefinitions());
			}
		}
	}

	/**
	 * Adds provider definitions from extensions to the container builder.
	 *
	 * @param ContainerBuilder<Container> $builder
	 */
	private function addExtensionProvidersDefinition(Application $app, ContainerBuilder $builder): void
	{
		/** @var array<string> $extensionList */
		$extensionList = $app->getConfig()->get('extensions', []);

		foreach ($extensionList as $extension) {
			if (is_string($extension)) {
				$extension = new $extension();
			}

			if (!$extension instanceof ExtensionInterface) {
				continue;
			}

			foreach ($extension->getProviders() as $provider) {
				/** @var BootstrapServiceProviderInterface|ServiceProviderInterface $provider */
				$provider = new $provider();

				$app->registerProvider($provider);

				if ($provider instanceof ServiceProviderInterface) {
					$builder->addDefinitions($provider->getDefinitions());
				}
			}
		}
	}
}
