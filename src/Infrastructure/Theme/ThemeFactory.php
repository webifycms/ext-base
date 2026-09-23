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

use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\ThemeInterface;
use Webify\Base\Infrastructure\Exception\ThemeException;
use Webify\Base\Infrastructure\Service\ThemeConfig;

use function file_exists;
use function file_get_contents;
use function is_array;
use function is_subclass_of;
use function json_decode;

/**
 * Builds a theme from a configured value.
 *
 * When the configured value is a class name (a full PHP theme), the factory
 * instantiates it directly with a zero-argument constructor. A PHP theme
 * class resolves its own configuration and root path (e.g. by reading the
 * `theme.json` next to the class file), so the factory does not wire any of
 * that up itself. The configured class must implement {@see ThemeInterface}.
 *
 * When the value is a plain string (an id or a directory path), a manifest
 * driven (PHP-less) generic {@see Theme} is built: the value is resolved to a
 * root directory, its `theme.json` is read and validated, and a {@see Theme}
 * instance is constructed with the manifest data and the resolved root path.
 */
final readonly class ThemeFactory
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private ConfigInterface $config,
		private ThemeManifestValidator $manifestValidator = new ThemeManifestValidator()
	) {}

	/**
	 * Builds a theme for the given configured value.
	 *
	 * @param string $value a theme class name, a theme id or a theme directory path
	 *
	 * @throws ThemeException when the value is a class that does not implement
	 *                        `ThemeInterface`, or when the theme root or
	 *                        manifest cannot be resolved
	 */
	public function fromConfig(string $value): ThemeInterface
	{
		if (!class_exists($value)) {
			return $this->fromManifest($value);
		}

		if (!is_subclass_of($value, ThemeInterface::class)) {
			throw ThemeException::notATheme($value);
		}

		/** @var class-string<ThemeInterface> $class */
		$class = $value;

		return new $class();
	}

	/**
	 * Builds the generic theme for a manifest-driven id or path.
	 */
	private function fromManifest(string $value): ThemeInterface
	{
		$themePath = $value;

		if (!is_dir($themePath)) {
			$themePath = $this->config->themesPath . DIRECTORY_SEPARATOR . $value;
		}

		if (!is_dir($themePath)) {
			throw ThemeException::configuredThemeNotFound($themePath);
		}

		$data = $this->readManifest($themePath . DIRECTORY_SEPARATOR . 'theme.json');

		return new Theme(new ThemeConfig($data), $themePath);
	}

	/**
	 * Reads and validates a `theme.json` manifest.
	 *
	 * @return array<string, mixed> the parsed manifest data
	 *
	 * @throws ThemeException when the file is missing, contains invalid JSON,
	 *                        or lacks a valid `id` or `name`
	 */
	private function readManifest(string $path): array
	{
		if (!file_exists($path)) {
			throw ThemeException::manifestNotFound($path);
		}

		/** @var array<string, mixed> $decoded */
		$decoded = json_decode((string) file_get_contents($path), true);

		if (!is_array($decoded)) {
			throw ThemeException::invalidManifest($path);
		}

		return $this->manifestValidator->validate($decoded, $path);
	}
}
