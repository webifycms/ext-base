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

use Webify\Base\Contract\ArraySearchHelper;
use Webify\Base\Infrastructure\Contract\ThemeConfigInterface;

/**
 * A generic, read-only view over a theme manifest (`theme.json`) data.
 *
 * This is the default `ThemeConfigInterface` implementation used by manifest
 * driven (PHP-less) themes that carry no dedicated configuration class.
 */
final class ThemeConfig implements ThemeConfigInterface
{
	use ArraySearchHelper;

	/**
	 * Used to accept `null` as a valid value.
	 */
	private const string NULL_VALUE = 'null';

	/**
	 * Used to store the found key and value pair when using the `has()` method.
	 *
	 * @var array<string, mixed>
	 */
	private array $cache = [];

	/**
	 * The constructor.
	 *
	 * @param array<string, mixed> $data the theme manifest data
	 */
	public function __construct(
		private readonly array $data = []
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function has(int|string $key): bool
	{
		if (array_key_exists($key, $this->cache)) {
			return true;
		}

		$found = $this->search((string) $key, $this->data, self::NULL_VALUE);

		if (self::NULL_VALUE !== $found) {
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
