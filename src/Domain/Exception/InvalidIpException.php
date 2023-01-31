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
 * An exception class can be used when IP validation failed.
 */
final class InvalidIpException extends TranslatableInvalidArgumentException
{
    /**
	 * The object constructor.
	 *
	 * @param string[] $params
	 */
	public function __construct(
		string $messageKey = 'invalid_ip',
		array $params = []
	) {
		parent::__construct($messageKey, $params);
	}
}
