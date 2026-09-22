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

namespace Webify\Base\Infrastructure\Container\Definitions;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Filesystem\Filesystem;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\{
	AssetsPipelineInterface,
	AssetsPublisherInterface,
	DefinitionsInterface,
	TemplateMapOverridesInterface,
	TemplateRendererInterface,
	TemplateResolverInterface,
	ThemeResolverInterface,
	UrlGeneratorInterface,
	ViewInterface,
};
use Webify\Base\Infrastructure\Service\{
	AssetsPublisher,
	TemplateMapOverrides,
	TemplateResolver,
	View,
};

use function DI\{autowire, factory, get};

/**
 * Theme related definitions.
 */
final class ServiceDefinitions implements DefinitionsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitions(): array
	{
		return [
			CacheItemPoolInterface::class        => factory(
				fn (ConfigInterface $config) => new FilesystemAdapter(
					'webify.theme',
					0,
					$config->cachePath
				)
			),
			AssetsPublisherInterface::class      => autowire(AssetsPublisher::class)
				->constructor(get(ConfigInterface::class), get(Filesystem::class)),
			TemplateMapOverridesInterface::class => factory(
				fn (
					ConfigInterface $config,
					CacheItemPoolInterface $cache,
					ThemeResolverInterface $resolver
				) => new TemplateMapOverrides(
					$config->get('templates.map', []),
					$resolver,
					$cache
				)
			),
			TemplateResolverInterface::class     => autowire(TemplateResolver::class)
				->constructor(get(TemplateMapOverridesInterface::class)),
			ViewInterface::class                 => autowire(View::class)
				->constructor(
					get(UrlGeneratorInterface::class),
					get(AssetsPipelineInterface::class),
					get(TemplateRendererInterface::class)
				),
		];
	}
}
