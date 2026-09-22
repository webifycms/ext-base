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
 * Exception thrown for container errors.
 */
final class ContainerException extends RuntimeException implements TranslatableExceptionInterface
{
	/**
	 * Private constructor enforces the use of the factory methods to initiate this exception.
	 *
	 * @param ExceptionTranslation $translation the translation object for this exception
	 * @param string               $message     the exception message (optional)
	 * @param null|Throwable       $previous    the previous exception
	 */
	private function __construct(
		public readonly ExceptionTranslation $translation,
		string $message = '',
		?Throwable $previous = null
	) {
		parent::__construct($message, 0, $previous);
	}

	/**
	 * Factory method to initiate when the container failed to build.
	 */
	public static function buildFailed(Throwable $exception): self
	{
		return new self(
			new ExceptionTranslation(
				'base.container',
				'container_build_failed'
			),
			'Container build failed.',
			$exception
		);
	}
}
