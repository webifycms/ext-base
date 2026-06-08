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
use Webify\Base\Domain\Exception\AccessDeniedException;

/**
 * AccessDeniedExceptionTest tests the functionality of the AccessDeniedException class.
 *
 * @internal
 */
#[CoversClass(AccessDeniedException::class)]
#[CoversMethod(AccessDeniedException::class, 'deniedFor')]
#[UsesClass(ExceptionTranslation::class)]
final class AccessDeniedExceptionTest extends TestCase
{
	/**
	 * Test the deniedFor factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testDeniedFor(): void
	{
		$exception = AccessDeniedException::deniedFor('read', 'user_123', 'doc_456');

		self::assertInstanceOf(AccessDeniedException::class, $exception);
		self::assertSame(
			'Access denied for action "read" on subject "user_123" and resource "doc_456".',
			$exception->getMessage()
		);
		self::assertSame('base.authorization', $exception->translation->group);
		self::assertSame('access_denied', $exception->translation->key);

		self::assertSame(
			[
				'action'     => 'read',
				'subjectId'  => 'user_123',
				'resourceId' => 'doc_456',
			],
			$exception->translation->params
		);
	}
}
