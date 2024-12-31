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

use Webify\Base\Domain\Service\Exception\TranslatableExceptionServiceInterface;

/**
 * Represents a translatable runtime exception that incorporates a message key
 * and additional parameters for localization or dynamic message generation.
 *
 * This class extends the base RuntimeException and provides additional
 * functionality for managing translatable messages. It allows for structured
 * access to message keys and parameters.
 */
class TranslatableRuntimeException extends \RuntimeException implements TranslatableExceptionServiceInterface
{
	/**
	 * The class constructor.
	 *
	 * @param array<string>|array{} $params additional items that should be included in the message can
	 *                                      be passed as `name => value` pairs
	 * @param ?int                  $code
	 */
	public function __construct(
		public readonly string $messageKey,
		public readonly array $params = [],
		public $code = null,
		public readonly ?\Throwable $previous = null
	) {
		$code ??= $this->previous?->getCode() ?? 0;

		parent::__construct('', $code, $previous);
	}

	public function getMessageKey(): string
	{
		return $this->messageKey;
	}

	public function getParam(string $key): ?string
	{
		return $this->params[$key] ?? null;
	}
}
