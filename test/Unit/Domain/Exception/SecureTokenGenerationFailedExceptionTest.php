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

namespace Webify\Base\Test\Unit\Domain\Exception;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Webify\Base\Domain\Contract\Translation\ExceptionTranslation;
use Webify\Base\Domain\Exception\SecureTokenGenerationFailedException;

/**
 * SecureTokenGenerationFailedExceptionTest tests the functionality of the SecureTokenGenerationFailedException class.
 *
 * @internal
 */
#[CoversClass(SecureTokenGenerationFailedException::class)]
#[CoversMethod(SecureTokenGenerationFailedException::class, 'create')]
#[UsesClass(ExceptionTranslation::class)]
final class SecureTokenGenerationFailedExceptionTest extends TestCase
{
	/**
	 * Test the create factory method creates an exception with a previous exception.
	 */
	#[Test]
	public function testCreate(): void
	{
		$previous  = new RuntimeException('random bytes generation failed');
		$exception = SecureTokenGenerationFailedException::create($previous);

		self::assertInstanceOf(SecureTokenGenerationFailedException::class, $exception);
		self::assertSame('Unable to generate secure token.', $exception->getMessage());
		self::assertSame($previous, $exception->previous);
		self::assertSame($previous, $exception->getPrevious());
		self::assertSame('user.base', $exception->translation->group);
		self::assertSame('secure_token_generation_failed', $exception->translation->key);
	}
}
