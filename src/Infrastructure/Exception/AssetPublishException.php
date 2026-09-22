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
use Throwable;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;
use Webify\Base\Contract\Translation\ExceptionTranslation;

/**
 * Exception thrown for asset publishing errors.
 */
final class AssetPublishException extends RuntimeException implements TranslatableExceptionInterface
{
	/**
	 * Private constructor enforces the use of the factory methods to initiate this exception.
	 *
	 * @param ExceptionTranslation $translation the translation object for this exception
	 * @param string               $message     the exception message (optional)
	 * @param null|Throwable       $previous    the previous exception (optional)
	 */
	private function __construct(
		public readonly ExceptionTranslation $translation,
		string $message = '',
		?Throwable $previous = null
	) {
		parent::__construct($message, 0, $previous);
	}

	/**
	 * Factory method for when the source directory does not exist.
	 */
	public static function sourceNotFound(string $path): self
	{
		return new self(
			new ExceptionTranslation(
				'base.asset_publish',
				'source_not_found',
				['path' => $path]
			),
			sprintf('Asset source directory "%s" does not exist.', $path)
		);
	}

	/**
	 * Factory method for when the target directory cannot be created.
	 */
	public static function targetNotWritable(string $path, ?Throwable $previous = null): self
	{
		return new self(
			new ExceptionTranslation(
				'base.asset_publish',
				'target_not_writable',
				['path' => $path]
			),
			sprintf('Asset target directory "%s" is not writable.', $path),
			$previous
		);
	}

	/**
	 * Factory method for when a file copy operation fails.
	 */
	public static function copyFailed(string $source, string $target, ?Throwable $previous = null): self
	{
		return new self(
			new ExceptionTranslation(
				'base.asset_publish',
				'copy_failed',
				['source' => $source, 'target' => $target]
			),
			sprintf('Failed to copy asset from "%s" to "%s".', $source, $target),
			$previous
		);
	}
}
