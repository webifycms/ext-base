<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Exception;

/**
 * TranslatableExceptionServiceInterface.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
interface TranslatableExceptionServiceInterface
{
	/**
	 * Returns the message key.
	 */
	public function getMessageKey(): string;

	/**
	 * Returns the value of the given param if exist otherwise returns null.
	 *
	 * @return ?string
	 */
	public function getParam(string $key): ?string;
}
