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
 * InvalidUniqueIdException is an exception class can be used to throw exception when supplied invalid unique ID.
 */
final class InvalidUniqueIdException extends TranslatableException
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
