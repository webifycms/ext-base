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
 * It's a runtime exception that's thrown when a file doesn't exist.
 */
final class FileNotExistException extends TranslatableRuntimeException
{
	/**
	 * The class constructor.
	 *
	 * @param string[] $params
	 */
	public function __construct(
		string $messageKey = 'file_not_exist',
		array $params = []
	) {
		parent::__construct($messageKey, $params);
	}
}
