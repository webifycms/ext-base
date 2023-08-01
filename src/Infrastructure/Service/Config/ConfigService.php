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

use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use yii\helpers\ArrayHelper;

/**
 * Config service implementation.
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
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function setConfig(string $key, mixed $config): self
	{
		ArrayHelper::setValue($this->config, $key, $config);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfig(?string $key = null, mixed $default = null): mixed
	{
		if (null === $key) {
			return $this->config;
		}

		return ArrayHelper::getValue($this->config, $key, $default);
	}
}
