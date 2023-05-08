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

namespace Webify\Base\Domain\Service\Exception;

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
