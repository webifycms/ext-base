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

namespace Webify\Base\Domain\Exception;

use InvalidArgumentException;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;
use Webify\Base\Contract\Translation\ExceptionTranslation;

/**
 * Thrown when an invalid theme manifest is encountered.
 */
final class InvalidThemeManifestException extends InvalidArgumentException implements TranslatableExceptionInterface
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
	 * Factory method to create an exception for an empty theme name.
	 */
	public static function forEmptyName(): InvalidThemeManifestException
	{
		return new self(
			new ExceptionTranslation(
				'base.theme_manifest',
				'theme_name'
			),
			'Theme name is not defined.'
		);
	}

	/**
	 * Factory method to create an exception for an empty theme version.
	 */
	public static function forEmptyVersion(): InvalidThemeManifestException
	{
		return new self(
			new ExceptionTranslation(
				'base.theme_manifest',
				'theme_version'
			),
			'Theme version is not defined.'
		);
	}

	/**
	 * Factory method to create an exception for an invalid template path is given.
	 */
	public static function forInvalidTemplatePath(string $path): InvalidThemeManifestException
	{
		return new self(
			new ExceptionTranslation(
				'base.theme_manifest',
				'template_path',
				['path' => $path]
			),
			sprintf('Template path "%s" is not a valid directory.', $path)
		);
	}
}
