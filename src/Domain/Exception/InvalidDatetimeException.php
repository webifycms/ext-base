<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
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
	 * @param string[] $params
	 */
	public function __construct(
		string $messageKey = 'invalid_datetime',
		array $params = []
	) {
		parent::__construct($messageKey, $params);
	}
}
