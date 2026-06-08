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

namespace Webify\Base\Test\Unit\Infrastructure\Exception;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\Contract\Translation\ExceptionTranslation;
use Webify\Base\Infrastructure\Exception\ApplicationEnvironmentException;

/**
 * ApplicationEnvironmentExceptionTest tests the functionality of the ApplicationEnvironmentException class.
 *
 * @internal
 */
#[CoversClass(ApplicationEnvironmentException::class)]
#[CoversMethod(ApplicationEnvironmentException::class, 'notDefinedInConfig')]
#[UsesClass(ExceptionTranslation::class)]
final class ApplicationEnvironmentExceptionTest extends TestCase
{
	/**
	 * Test the notDefinedInConfig factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testNotDefinedInConfig(): void
	{
		$exception = ApplicationEnvironmentException::notDefinedInConfig();

		self::assertInstanceOf(ApplicationEnvironmentException::class, $exception);
		self::assertSame('Application environment not defined in the configurations.', $exception->getMessage());
		self::assertSame('base.environment', $exception->translation->group);
		self::assertSame('environment_not_defined', $exception->translation->key);
	}
}
