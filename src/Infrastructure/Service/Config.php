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
use Webify\Base\Contract\ArraySearchHelper;

/**
 * Config is a service that provides access to the application configuration.
 */
final class Config implements ConfigInterface
{
	use ArraySearchHelper;

	/**
	 * Used to accept `null` as a valid value.
	 */
	private const string NULL_VALUE = 'null';

	/**
	 * {@inheritdoc}
	 */
	public string $basePath {
		get => $this->basePath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $runtimePath {
		get => $this->runtimePath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $configPath {
		get => $this->configPath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $cachePath {
		get => $this->cachePath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $logPath {
		get => $this->logPath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $extensionsPath {
		get => $this->extensionsPath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $themesPath {
		get => $this->themesPath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $publicPath {
		get => $this->publicPath;
	}

	/**
	 * {@inheritdoc}
	 */
	public string $baseUrl {
		get => $this->baseUrl;
	}

	/**
	 * Used to store the found key and value pair when using the `has()` method.
	 *
	 * @var array<string, mixed>
	 */
	private array $cache = [];

	/**
	 * The constructor.
	 *
	 * @param array<string, mixed> $config the configuration list
	 */
	public function __construct(
		private readonly array $config
	) {
		$this->basePath       = rtrim($this->get('basePath', dirname(__DIR__, 3)), '/');
		$this->configPath     = rtrim(
			$this->get('configPath', $this->basePath . DIRECTORY_SEPARATOR . self::CONFIG_DIR),
			'/'
		);
		$this->publicPath     = rtrim(
			$this->get('publicPath', $this->basePath . DIRECTORY_SEPARATOR . self::PUBLIC_DIR),
			'/'
		);
		$this->baseUrl        = rtrim((string) $this->get('baseUrl', ''), '/');
		$this->runtimePath    = rtrim(
			$this->get('runtimePath', $this->basePath . DIRECTORY_SEPARATOR . self::RUNTIME_DIR),
			'/'
		);
		// The "extensions" and "themes" are not configurable
		$this->extensionsPath = $this->basePath . DIRECTORY_SEPARATOR . self::EXTENSIONS_DIR;
		$this->themesPath     = $this->basePath . DIRECTORY_SEPARATOR . self::THEMES_DIR;
		// Subdirectories and not configurable too
		$this->cachePath      = $this->runtimePath . DIRECTORY_SEPARATOR . self::CACHE_DIR;
		$this->logPath        = $this->runtimePath . DIRECTORY_SEPARATOR . self::LOG_DIR;
	}

	/**
	 * {@inheritDoc}
	 */
	public function with(array $config): ConfigInterface
	{
		return new self(array_merge($this->config, $config));
	}

	/**
	 * {@inheritDoc}
	 */
	public function has(int|string $key): bool
	{
		if (array_key_exists($key, $this->cache)) {
			return true;
		}

		// Check if the key exists in the configuration
		$found = $this->search((string) $key, $this->config, self::NULL_VALUE);

		if (self::NULL_VALUE !== $found) {
			// Cache the found key and value pair for future use
			$this->cache[(string) $key] = $found;

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(int|string $key, mixed $default = null): mixed
	{
		if ($this->has($key)) {
			return $this->cache[(string) $key];
		}

		return $default;
	}
}
