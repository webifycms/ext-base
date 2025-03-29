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

use Throwable;

/**
 * It's a runtime exception that's can use to throw when a file doesn't exist.
 */
final class FileNotExistException extends TranslatableRuntimeException
{
	public const MESSAGE_KEY = 'base.file_not_exist';

	/**
	 * The class constructor.
	 *
	 * @param array<string>|array{} $params
	 */
	public function __construct(
		string $messageKey = self::MESSAGE_KEY,
		array $params = [],
		?int $code = null,
		?Throwable $previous = null
	) {
		parent::__construct($messageKey, $params, $code, $previous);
	}
}
