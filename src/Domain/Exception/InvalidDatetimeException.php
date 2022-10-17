<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;

/**
 * InvalidDatetimeException.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
final class InvalidDatetimeException extends TranslatableException
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
