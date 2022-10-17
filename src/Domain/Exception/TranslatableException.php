<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;

use Exception;
use OneCMS\Base\Domain\Service\Exception\TranslatableExceptionServiceInterface;

/**
 * TranslatableException.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
class TranslatableException extends Exception implements TranslatableExceptionServiceInterface
{
	/**
	 * The class constructor.
	 *
	 * @param string[] $params additional items that should be included in the message can be passed as `name => value`
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
