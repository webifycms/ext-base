<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Provider;

use Psr\Container\ContainerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Container\Definitions\{
	ConsoleDefinitions,
	EventDefinitions,
	HttpDefinitions,
	LoggingDefinitions,
	ServiceDefinitions,
};
use Webify\Base\Infrastructure\Contract\{
	AssetsPublisherInterface,
	BootstrapServiceProviderInterface,
	ExtensionInterface,
	ServiceProviderInterface,
	ThemeResolverInterface,
};

/**
 * Base service provider that merges the grouped container definitions.
 */
final class BaseService implements ServiceProviderInterface, BootstrapServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getDefinitions(): array
	{
		return [
			...HttpDefinitions::getDefinitions(),
			...ConsoleDefinitions::getDefinitions(),
			...EventDefinitions::getDefinitions(),
			...LoggingDefinitions::getDefinitions(),
			...ServiceDefinitions::getDefinitions(),
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap(ContainerInterface $container): void
	{
		// Let's publish built assets of all
		$config              = $container->get(ConfigInterface::class);
		$publisher           = $container->get(AssetsPublisherInterface::class);
		$resolver            = $container->get(ThemeResolverInterface::class);
		$themeAssetsProvider = $resolver->active()->getAssetsProvider();

		if (null !== $themeAssetsProvider) {
			$publisher->publish($themeAssetsProvider);
		}

		/** @var array<string> $extensions */
		$extensions = $config->get('extensions', []);

		foreach ($extensions as $extension) {
			if (!$container->has($extension)) {
				continue;
			}

			/** @var ExtensionInterface $extensionInstance */
			$extensionInstance = $container->get($extension);

			if (null !== $extensionInstance->getAssetsProvider()) {
				$publisher->publish($extensionInstance->getAssetsProvider());
			}
		}
	}
}
