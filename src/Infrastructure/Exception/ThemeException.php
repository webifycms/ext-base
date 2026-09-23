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

namespace Webify\Base\Infrastructure\Exception;

use RuntimeException;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;
use Webify\Base\Contract\Translation\ExceptionTranslation;
use Webify\Base\Infrastructure\Contract\ThemeInterface;

/**
 * Exception thrown for theme related errors.
 */
final class ThemeException extends RuntimeException implements TranslatableExceptionInterface
{
	/**
	 * Private constructor enforces the use of the factory methods to initiate this exception.
	 *
	 * @param ExceptionTranslation $translation the translation object for this exception
	 * @param string               $message     the exception message (optional)
	 */
	private function __construct(
		public readonly ExceptionTranslation $translation,
		string $message = ''
	) {
		parent::__construct($message);
	}

	/**
	 * Factory method to initiate when the invalid theme manifest JSON found.
	 *
	 * @param string $themeId the theme ID which is not found
	 */
	public static function configuredThemeNotFound(string $themeId): self
	{
		return new self(
			new ExceptionTranslation(
				'base.theme',
				'configred_theme_not_found',
				['themeId' => $themeId]
			),
			sprintf('Theme %s not found', $themeId)
		);
	}

	/**
	 * Factory method to initiate when no default theme is configured.
	 */
	public static function missingDefault(): self
	{
		return new self(
			new ExceptionTranslation(
				'base.theme',
				'missing_default_theme'
			),
			'No default theme is configured in the application config.'
		);
	}

	/**
	 * Factory method to initiate when the configured theme class does not
	 * implement the `ThemeInterface`.
	 *
	 * @param string $class the configured theme class name
	 */
	public static function notATheme(string $class): self
	{
		return new self(
			new ExceptionTranslation(
				'base.theme',
				'not_a_theme',
				['class' => $class]
			),
			sprintf('Configured theme class "%s" must implement %s.', $class, ThemeInterface::class)
		);
	}

	/**
	 * Factory method to initiate when a theme manifest is missing or unable to read.
	 *
	 * @param string $path the path to the invalid `theme.json` file
	 */
	public static function manifestNotFound(string $path): self
	{
		return new self(
			new ExceptionTranslation(
				'base.theme',
				'theme_manifest',
				['path' => $path]
			),
			sprintf('Unable to read theme manifest from "%s"', $path)
		);
	}

	/**
	 * Factory method to initiate when a theme manifest is missing or invalid.
	 *
	 * @param string $path the path to the invalid `theme.json` file
	 */
	public static function invalidManifest(string $path): self
	{
		return new self(
			new ExceptionTranslation(
				'base.theme',
				'invalid_theme_manifest',
				['path' => $path]
			),
			sprintf('Invalid manifest data from "%s"', $path)
		);
	}
}
