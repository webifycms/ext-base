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

namespace Webify\Base\Application\Service;

use Webify\Base\Contract\KeyValueReaderInterface;

/**
 * ConfigInterface defines the contract for holding configurations.
 */
interface ConfigInterface extends KeyValueReaderInterface
{
	/**
	 * The application cache and log directories.
	 */
	public const string CACHE_DIR = 'cache';
	public const string LOG_DIR   = 'log';

	/**
	 * @var string the base path of the application and support getter
	 */
	public string $basePath {
		get;
	}

	/**
	 * @var string the runtime path of the application and support getter
	 */
	public string $runtimePath {
		get;
	}

	/**
	 * @var string the config path of the application and support getter
	 */
	public string $configPath {
		get;
	}

	/**
	 * @var string the cache path of the application and support getter
	 */
	public string $cachePath {
		get;
	}

	/**
	 * @var string the log path of the application and support getter
	 */
	public string $logPath {
		get;
	}

	/**
	 * @var string the application base URL and support getter
	 */
	public string $baseUrl {
		get;
	}

	/**
	 * Create a new instance with the given configuration merged in.
	 *
	 * @param array<string, mixed> $config the configuration to merge
	 *
	 * @return ConfigInterface a new instance with the merged configuration
	 */
	public function with(array $config): ConfigInterface;
}
