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

/**
 * It's a custom exception class that extends the `TranslatableInvalidArgumentException` class.
 */
final class InvalidDatetimeException extends TranslatableInvalidArgumentException
{
	/**
	 * The class constructor.
	 *
	 * @param string   $messageKey
	 * @param string[] $params
	 */
	public function __construct(
		$messageKey = 'invalid_datetime',
		$params = []
	) {
		parent::__construct($messageKey, $params);
	}
}
