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

namespace Webify\Base\Infrastructure\Service\Validator;

use Webify\Base\Domain\Service\Validator\EmailValidatorServiceInterface;
use yii\validators\EmailValidator;

final class EmailValidatorService implements EmailValidatorServiceInterface
{
	public function __construct(
		public readonly EmailValidator $validator
	) {}

	public function isValid(mixed $value): bool
	{
		return $this->validator->validate($value);
	}
}
