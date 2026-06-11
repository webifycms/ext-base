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
		get {
			return $this->basePath;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public string $runtimePath {
		get {
			return $this->runtimePath;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public string $configPath {
		get {
			return $this->configPath;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public string $cachePath {
		get {
			return $this->cachePath;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public string $logPath {
		get {
			return $this->logPath;
		}
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
		$this->basePath    = rtrim($this->get('basePath'), '/');
		$this->runtimePath = rtrim($this->get('runtimePath'), '/');
		$this->configPath  = rtrim($this->get('configPath'), '/');
		$this->cachePath   = $this->runtimePath . DIRECTORY_SEPARATOR . self::CACHE_DIR;
		$this->logPath     = $this->runtimePath . DIRECTORY_SEPARATOR . self::LOG_DIR;
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
