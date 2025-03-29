<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author  Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service\Config;

use Throwable;
use Webify\Base\Domain\Exception\ConfigNotFoundException;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use yii\helpers\ArrayHelper;

/**
 * A service for handling configuration data.
 * This class provides methods to store, retrieve, and manage configurations
 * with support for hierarchical keys and default values.
 */
final class ConfigService implements ConfigServiceInterface
{
	/**
	 * The object constructor.
	 *
	 * @param array<string, mixed> $config
	 */
	public function __construct(
		private array $config
	) {}

	public function setConfig(string $key, mixed $config): self
	{
		ArrayHelper::setValue($this->config, $key, $config);

		return $this;
	}

	public function getConfig(?string $key = null, mixed $default = null): mixed
	{
		// if `$key` not specified, returns the entire configurations
		if (null === $key) {
			return $this->config;
		}

		try {
			return ArrayHelper::getValue($this->config, $key, $default);
		} catch (Throwable $e) {
			throw new ConfigNotFoundException(
				ConfigNotFoundException::MESSAGE_KEY,
				['config_key' => $key],
				$e->getCode(),
				$e
			);
		}
	}
}
