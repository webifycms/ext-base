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
 * Interface for defining validation services to check the validity of values.
 */
interface ValidatorServiceInterface
{
    /**
     * Validates the given value based on specified criteria.
     *
     * @param mixed $value The value to be validated.
     * @return bool Returns true if the value is valid, otherwise false.
     */
	public function isValid(mixed $value): bool;
}
