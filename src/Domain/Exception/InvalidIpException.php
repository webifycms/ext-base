<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @license Copyright (c) 2022 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\Exception;

use Throwable;

/**
 * An exception class can be used when IP validation failed.
 */
final class InvalidIpException extends TranslatableInvalidArgumentException
{
	public const MESSAGE_KEY = 'base.invalid_ip';

	/**
	 * The object constructor.
	 *
	 * @param array<string>|array{} $params
	 */
	public function __construct(
		string $messageKey = self::MESSAGE_KEY,
		array $params = [],
		?int $code = null,
		?Throwable $previous = null
	) {
		parent::__construct($messageKey, $params, $code, $previous);
	}
}
