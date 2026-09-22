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

use Webify\Base\Infrastructure\Exception\ThemeException;

use function is_string;

/**
 * Validates a theme manifest (`theme.json`) payload.
 *
 * A manifest is considered valid only when it declares both an `id` and a
 * `name`, each a non-empty string.
 */
final class ThemeManifestValidator
{
	/**
	 * Validates the given theme manifest data.
	 *
	 * @param array<string, mixed> $data the raw `theme.json` data
	 *
	 * @return array<string, mixed> the validated manifest data
	 *
	 * @throws ThemeException when the manifest lacks a valid `id` or `name`
	 */
	public function validate(array $data, string $path): array
	{
		if ($this->isMissingRequiredField($data, 'id')) {
			throw ThemeException::invalidManifest($path);
		}

		if ($this->isMissingRequiredField($data, 'name')) {
			throw ThemeException::invalidManifest($path);
		}

		return $data;
	}

	/**
	 * Determines whether the given manifest field is absent, not a string, or
	 * an empty string.
	 *
	 * @param array<string, mixed> $data  the manifest data
	 * @param string               $field the field name to check
	 */
	private function isMissingRequiredField(array $data, string $field): bool
	{
		$value = $data[$field] ?? null;

		return !is_string($value) || '' === $value;
	}
}
