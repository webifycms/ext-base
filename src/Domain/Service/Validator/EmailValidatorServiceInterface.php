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
 * Interface representing a service to validate email addresses.
 *
 * Extends the ValidatorServiceInterface for general validation capabilities
 * while specifically focusing on email validation functionality.
 */
interface EmailValidatorServiceInterface extends ValidatorServiceInterface {}
