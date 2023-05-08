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
 * It's a custom exception class that's thrown when a unique ID is invalid.
 */
final class InvalidUniqueIdException extends TranslatableInvalidArgumentException
{
	/**
	 * The object constructor.
	 *
	 * @param string[] $params
	 */
	public function __construct(
		string $messageKey = 'invalid_unique_id',
		array $params = []
	) {
		parent::__construct($messageKey, $params);
	}
}
