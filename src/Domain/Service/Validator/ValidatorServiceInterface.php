<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author  Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\Service\Validator;

/**
 * Undocumented interface.
 */
interface ValidatorServiceInterface
{
	/**
	 * Check the given value is valid.
	 */
	public function isValid(mixed $value): bool;
}
