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

use Webify\Base\Domain\Service\Exception\TranslatableExceptionServiceInterface;

/**
 * It's a translatable invalid argument exception class that can be extends.
 */
class TranslatableInvalidArgumentException extends \InvalidArgumentException implements TranslatableExceptionServiceInterface
{
	/**
	 * The class constructor.
	 *
	 * @param string[] $params additional items that should be included in the message can be passed as `name => value` pairs
	 */
	public function __construct(
		public readonly string $messageKey,
		public readonly array $params = []
	) {
		parent::__construct();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMessageKey(): string
	{
		return $this->messageKey;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParam(string $key): ?string
	{
		return $this->params[$key] ?? null;
	}
}
