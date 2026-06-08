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

namespace Webify\Base\Application\Service;

use Webify\Base\Contract\KeyValueReaderInterface;

/**
 * ConfigInterface defines the contract for holding configurations.
 */
interface ConfigInterface extends KeyValueReaderInterface
{
	/**
	 * @var string the application cache and log directories
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
}
