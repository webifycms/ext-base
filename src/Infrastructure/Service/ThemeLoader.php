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

namespace Webify\Base\Infrastructure\Service;

use DI\{Container, ContainerBuilder};
use Webify\Base\Infrastructure\Contract\{ThemeInterface, ThemeResolverInterface};

/**
 * Loads the theme(s) into the container.
 *
 * The default theme's provider is always registered first — it supplies the
 * fallback renderer/asset pipeline/behaviour. When an active theme differs from
 * the default, its provider is registered afterwards so its bindings win via
 * ordinary last-registration-wins DI semantics (per-active-theme bindings).
 *
 * The resolved theme resolver and the active theme are bound so services can
 * depend on the resolver contract (the active/default fallback) rather than
 * injecting `ThemeInterface` directly.
 */
final readonly class ThemeLoader
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private ThemeResolverInterface $resolver
	) {}

	/**
	 * Load the default and (if configured and different) active theme providers
	 * and definitions.
	 *
	 * @param ContainerBuilder<Container> $builder
	 */
	public function load(Application $app, ContainerBuilder $builder): void
	{
		$default = $this->resolver->default();
		$active  = $this->resolver->active();

		$this->registerTheme($app, $builder, $default);

		if ($active->getId() !== $default->getId()) {
			$this->registerTheme($app, $builder, $active);
		}

		$builder->addDefinitions([
			ThemeResolverInterface::class => $this->resolver,
			ThemeInterface::class         => $active,
		]);
	}

	/**
	 * Registers a theme's provider and container definitions when it has one.
	 *
	 * @param ContainerBuilder<Container> $builder
	 */
	private function registerTheme(Application $app, ContainerBuilder $builder, ThemeInterface $theme): void
	{
		$provider = $theme->getProvider();

		if (null === $provider) {
			return;
		}

		$app->registerProvider($provider);
		$builder->addDefinitions($provider->getDefinitions());
	}
}
