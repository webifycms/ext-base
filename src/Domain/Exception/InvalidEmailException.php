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
 * InvalidEmailException will be used to throw en error when the supplied email address is invalid.
 */
final class InvalidEmailException extends TranslatableException
{
	/**
	 * The object constructor.
	 *
	 * @param string   $messageKey
	 * @param string[] $params
	 */
	public function __construct(
		$messageKey = 'invalid_email',
		$params = []
	) {
		parent::__construct($messageKey, $params);
	}
}
