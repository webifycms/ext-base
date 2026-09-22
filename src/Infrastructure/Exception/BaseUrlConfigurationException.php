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

/**
 * Exception thrown when base URL configuration errors.
 */
final class BaseUrlConfigurationException extends RuntimeException implements TranslatableExceptionInterface
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
	 * Factory method for when the configured base URL string cannot be parsed.
	 *
	 * @param string $value the invalid base URL value from the configuration
	 */
	public static function forInvalid(string $value): BaseUrlConfigurationException
	{
		return new self(
			new ExceptionTranslation(
				'base.configuration',
				'invalid_base_url',
				['baseUrl' => $value]
			),
			sprintf('Unable to parse base URL "%s" from configuration.', $value)
		);
	}

	/**
	 * Factory method for when an absolute or network-path URL is requested but no base URL was configured.
	 */
	public static function notConfigured(): BaseUrlConfigurationException
	{
		return new self(
			new ExceptionTranslation(
				'base.configuration',
				'base_url_not_configured'
			),
			'Cannot generate an absolute URL because no base URL is configured (config key "baseUrl").'
		);
	}
}
