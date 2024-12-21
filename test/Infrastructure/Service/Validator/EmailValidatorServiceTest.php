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

namespace Webify\Base\Test\Infrastructure\Service\Validator;

use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Service\Validator\EmailValidatorService;
use yii\validators\EmailValidator;

/**
 * @coversDefaultClass \Webify\Base\Infrastructure\Service\Validator\EmailValidatorService
 *
 * @internal
 */
final class EmailValidatorServiceTest extends TestCase
{
	/**
	 * @covers ::isValid
	 */
	public function testItCanValidatesEmail(): void
	{
		$service = new EmailValidatorService(new EmailValidator());

		$this->assertTrue($service->isValid('valid@email.com'));
		$this->assertFalse($service->isValid('invalid@email'));
	}
}
