<?php
/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;

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
