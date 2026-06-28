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

namespace Webify\Base\Contract;

/**
 * The trait that provides a method for searching an array
 * using a key or dot-notation path recursively.
 */
trait ArraySearchHelper
{
	/**
	 * Search an array using a key or dot-notation path, recursively.
	 *
	 * @param string               $path    the array key or dot-notation path to search for (e.g., 'user', 'user.name')
	 * @param array<string, mixed> $array   the array to search in
	 * @param null|mixed           $default the default value to return if the key is not found
	 *
	 * @return mixed the value found at the specified key or path, or the default value if not found
	 */
	public function search(string $path, array $array, mixed $default = null): mixed
	{
		if (array_key_exists($path, $array)) {
			return $array[$path];
		}

		if (!str_contains($path, '.')) {
			return $default;
		}

		$segments = explode('.', $path);

		if (array_key_exists($segments[0], $array) && !is_array($array[$segments[0]])) {
			return $default;
		}

		$firstKey = (string) array_shift($segments);
		$subArray = $array[$firstKey];

		if (!is_array($subArray)) {
			return $default;
		}

		return $this->search(implode('.', $segments), $subArray, $default);
	}
}
