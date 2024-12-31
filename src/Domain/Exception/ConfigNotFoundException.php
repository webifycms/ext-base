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
 * Exception thrown when a required configuration is not found.
 *
 * This class extends TranslatableRuntimeException, allowing for error messages
 * that can be translated to different languages or locales.
 */
final class ConfigNotFoundException extends TranslatableRuntimeException
{
	public const MESSAGE_KEY = 'base.config_not_found';

	/**
	 * The class constructor.
	 *
	 * @param array<string>|array{} $params
	 */
	public function __construct(
		string $messageKey = self::MESSAGE_KEY,
		array $params = [],
		?int $code = null,
		?\Throwable $previous = null
	) {
		parent::__construct($messageKey, $params, $code, $previous);
	}
}
