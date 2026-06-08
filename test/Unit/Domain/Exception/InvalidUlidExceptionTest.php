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
use Webify\Base\Domain\Contract\Translation\ExceptionTranslation;
use Webify\Base\Domain\Exception\InvalidUlidException;

/**
 * InvalidUlidExceptionTest tests the functionality of the InvalidUlidException class.
 *
 * @internal
 */
#[CoversClass(InvalidUlidException::class)]
#[CoversMethod(InvalidUlidException::class, 'forInvalidUlid')]
#[UsesClass(ExceptionTranslation::class)]
final class InvalidUlidExceptionTest extends TestCase
{
	/**
	 * Test the forInvalidUlid factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testForInvalidUlid(): void
	{
		$exception = InvalidUlidException::forInvalidUlid('invalid-ulid');

		self::assertInstanceOf(InvalidUlidException::class, $exception);
		self::assertSame(
			'Invalid ULID "invalid-ulid" given for normalization',
			$exception->getMessage()
		);
		self::assertSame('base.ulid', $exception->translation->group);
		self::assertSame('invalid_ulid', $exception->translation->key);
		self::assertSame(['ulid' => 'invalid-ulid'], $exception->translation->params);
	}
}
