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

use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\{ThemeInterface, ThemeResolverInterface};
use Webify\Base\Infrastructure\Exception\ThemeException;
use Webify\Base\Infrastructure\Theme\ThemeFactory;

use function array_map;

/**
 * Resolves the active theme from the app configuration.
 *
 * The `themes` config is a map:
 *
 * ```php
 * 'themes' => [
 *     'default' => Canvas::class,   // always present
 *     'active'  => MyTheme::class|'theme-id',  // optional — falls back to `default`
 * ],
 * ```
 *
 * This resolver is the single place that turns the configured theme class names
 * (or manifest theme ids/paths) into {@see ThemeInterface} instances, and it is
 * what services should depend on instead of injecting `ThemeInterface` directly
 * (which cannot represent the default-vs-active/fallback distinction). Resolved
 * themes are memoized so each configured theme is built at most once per
 * application lifetime.
 */
final class ThemeResolver implements ThemeResolverInterface
{
	/**
	 * Memorize resolved themes by config key so the same class is never built twice.
	 *
	 * @var array<string, ThemeInterface>
	 */
	private array $cache = [];

	/**
	 * The constructor.
	 */
	public function __construct(
		private readonly ConfigInterface $config,
		private readonly ThemeFactory $factory
	) {}

	/**
	 * Returns the currently active theme, falling back to the default theme
	 * when no active theme is configured.
	 *
	 * @throws ThemeException when neither `default` nor `active` is set
	 */
	public function active(): ThemeInterface
	{
		return $this->resolve('active') ?? $this->default();
	}

	/**
	 * Returns the default theme.
	 *
	 * @throws ThemeException when `default` is not set
	 */
	public function default(): ThemeInterface
	{
		$theme = $this->resolve('default');

		if (null === $theme) {
			throw ThemeException::missingDefault();
		}

		return $theme;
	}

	/**
	 * Returns the effective themes in resolution order: the default theme first,
	 * then the active theme when it differs, so the active theme can override.
	 *
	 * @return list<ThemeInterface>
	 */
	public function themes(): array
	{
		$default = $this->default();
		$active  = $this->active();

		if ($active->getId() === $default->getId()) {
			return [$default];
		}

		return [$default, $active];
	}

	/**
	 * {@inheritDoc}
	 */
	public function themeTemplatePaths(): array
	{
		return array_map(
			fn (ThemeInterface $theme) => $theme->getTemplatePath()->toNative(),
			$this->themes()
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function themeTemplateMaps(): array
	{
		return array_map(
			fn (ThemeInterface $theme) => $theme->getTemplateMap(),
			$this->themes()
		);
	}

	/**
	 * Resolves and memorizes the theme for the given config key.
	 */
	private function resolve(string $key): ?ThemeInterface
	{
		if (array_key_exists($key, $this->cache)) {
			return $this->cache[$key];
		}

		/** @var array<string, string> $themes */
		$themes = $this->config->get('themes', []);
		$value  = $themes[$key] ?? null;

		if (!is_string($value) || '' === $value) {
			return null;
		}

		return $this->cache[$key] = $this->build($value);
	}

	/**
	 * Builds a {@see ThemeInterface} from a configured value via
	 * {@see ThemeFactory::fromConfig()}.
	 */
	private function build(string $value): ThemeInterface
	{
		return $this->factory->fromConfig($value);
	}
}
