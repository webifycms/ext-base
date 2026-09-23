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

namespace Webify\Base\Infrastructure\Theme;

use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Contract\{
	AssetsProviderInterface,
	ServiceProviderInterface,
	ThemeConfigInterface,
	ThemeInterface,
};
use Webify\Base\Infrastructure\Service\ThemeAssetsProvider;

use function is_dir;
use function is_string;

/**
 * A generic, manifest driven (PHP-less) theme.
 *
 * Its identity (id/name/version), template path and template overrides are all
 * declared in `theme.json`. It has no service provider and ships its built
 * assets (if any) under `dist/`. An active theme configured as a plain id/path
 * resolves to this concrete class.
 */
final readonly class Theme implements ThemeInterface
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private ThemeConfigInterface $config,
		private string $rootPath
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function getId(): string
	{
		return (string) $this->config->get('id');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return (string) $this->config->get('name', $this->getId());
	}

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): string
	{
		return (string) $this->config->get('version', '0.0.0');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfig(): ThemeConfigInterface
	{
		return $this->config;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRootPath(): string
	{
		return $this->rootPath;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTemplatePath(): TemplatePath
	{
		return TemplatePath::fromString($this->rootPath . '/templates');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array<string, TemplatePath>
	 */
	public function getTemplateMap(): array
	{
		$map = [];

		/** @var array<string, mixed> $templates */
		$templates = $this->config->get('templates', []);

		foreach ($templates as $extensionId => $path) {
			if (is_string($extensionId) && is_string($path)) {
				$map[$extensionId] = TemplatePath::fromString(
					$this->resolvePath($path)
				);
			}
		}

		return $map;
	}

	/**
	 * A data-only theme has no service provider.
	 */
	public function getProvider(): ?ServiceProviderInterface
	{
		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAssetsProvider(): ?AssetsProviderInterface
	{
		return is_dir($this->rootPath . '/dist')
			? new ThemeAssetsProvider($this)
			: null;
	}

	/**
	 * Resolves a manifest path to an absolute path, relative paths being
	 * anchored at the theme root.
	 */
	private function resolvePath(string $path): string
	{
		if ('' !== $path && '/' === $path[0]) {
			return $path;
		}

		return rtrim($this->rootPath, '/') . '/' . ltrim($path, '/');
	}
}
