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

namespace OneCMS\Base\Domain\Service\Exception;

/**
 * Interface that helps to identity the exceptions that message can be translatable.
 */
interface TranslatableExceptionServiceInterface
{
	/**
	 * Returns the message key.
	 */
	public function getMessageKey(): string;

	/**
	 * Returns the value of the given param if exist otherwise returns null.
	 */
	public function getParam(string $key): ?string;
}
