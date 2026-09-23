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
 * Thrown when an invalid extension slug is encountered.
 */
final class InvalidExtensionSlugException extends InvalidArgumentException implements TranslatableExceptionInterface
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
	 * Factory method to create an exception for an invalid extension slug.
	 *
	 * @param string $slug the slug string that was deemed invalid
	 */
	public static function forInvalid(string $slug): InvalidExtensionSlugException
	{
		return new self(
			new ExceptionTranslation(
				'base.extension_slug',
				'extension_slug',
				['slug' => $slug]
			),
			sprintf('Invalid slug "%s" given for normalization', $slug)
		);
	}
}
