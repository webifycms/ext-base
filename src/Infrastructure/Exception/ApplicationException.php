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

namespace Webify\Base\Infrastructure\Exception;

use RuntimeException;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;
use Webify\Base\Contract\Translation\ExceptionTranslation;

/**
 * Exception thrown for application path/config errors.
 */
final class ApplicationException extends RuntimeException implements TranslatableExceptionInterface
{
	/**
	 * Private constructor enforces the use of the factory methods to initiate this exception.
	 *
	 * @param ExceptionTranslation $translation the translation object for this exception
	 * @param string               $message     the exception message (optional)
	 */
	private function __construct(
		public readonly ExceptionTranslation $translation,
		string $message = '',
	) {
		parent::__construct($message);
	}

	/**
	 * Factory method to initiate when the application base path is not defined in the configurations.
	 */
	public static function basePathNotDefined(): self
	{
		return new self(
			new ExceptionTranslation(
				'base.config',
				'bath_path_not_defined'
			),
			'Application base path not defined in the configurations.'
		);
	}

	/**
	 * Factory method to initiate when the application runtime path is not defined in the configurations.
	 */
	public static function runtimePathNotDefined(): self
	{
		return new self(
			new ExceptionTranslation(
				'base.config',
				'runtime_path_not_defined'
			),
			'Application runtime path not defined in the configurations.'
		);
	}

	/**
	 * Factory method to initiate when the application runtime path is not writable.
	 */
	public static function runtimePathIsNotWritable(string $path): self
	{
		return new self(
			new ExceptionTranslation(
				'base.config',
				'runtime_path_is_not_writable',
				['path' => $path]
			),
			sprintf('Application runtime path "%s" is not writable.', $path)
		);
	}

	/**
	 * Factory method to initiate when failed to create application runtime paths.
	 */
	public static function unableToCreateRuntimePaths(): self
	{
		return new self(
			new ExceptionTranslation(
				'base.config',
				'failed_to_create_runtime_paths'
			),
			'Failed to create application runtime paths.'
		);
	}
}
