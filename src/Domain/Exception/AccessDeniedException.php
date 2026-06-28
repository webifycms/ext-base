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

namespace Webify\Base\Domain\Exception;

use RuntimeException;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;
use Webify\Base\Contract\Translation\ExceptionTranslation;

/**
 * Exception thrown when an access denied error occurs in the authorization domain.
 */
final class AccessDeniedException extends RuntimeException implements TranslatableExceptionInterface
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
	 * Factory method to initiate this with a default message.
	 *
	 * @param string $action     the action being performed
	 * @param string $subjectId  the ID of the subject
	 * @param string $resourceId the ID of the resource
	 */
	public static function deniedFor(string $action, string $subjectId, string $resourceId): self
	{
		return new self(
			new ExceptionTranslation(
				'base.authorization',
				'access_denied',
				[
					'action'     => $action,
					'subjectId'  => $subjectId,
					'resourceId' => $resourceId,
				]
			),
			sprintf(
				'Access denied for action "%s" on subject "%s" and resource "%s".',
				$action,
				$subjectId,
				$resourceId
			)
		);
	}
}
