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
 * An exception class can be used when unique id validation failed.
 */
final class InvalidUniqueIdException extends TranslatableInvalidArgumentException
{
    public const MESSAGE_KEY = 'base.invalid_unique_id';

	/**
	 * The object constructor.
	 *
	 * @param string[] $params
	 */
	public function __construct(
        string      $messageKey = self::MESSAGE_KEY,
        array       $params = [],
        int         $code = 0,
        ?\Throwable $previous = null
	) {
		parent::__construct($messageKey, $params, $code, $previous);
	}
}
