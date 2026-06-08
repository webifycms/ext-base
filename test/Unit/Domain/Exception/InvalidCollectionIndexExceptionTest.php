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
use Webify\Base\Domain\Exception\InvalidCollectionIndexException;

/**
 * InvalidCollectionIndexExceptionTest tests the functionality of the InvalidCollectionIndexException class.
 *
 * @internal
 */
#[CoversClass(InvalidCollectionIndexException::class)]
#[CoversMethod(InvalidCollectionIndexException::class, 'forMissingIndex')]
#[UsesClass(ExceptionTranslation::class)]
final class InvalidCollectionIndexExceptionTest extends TestCase
{
	/**
	 * Test the forMissingIndex factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testForMissingIndex(): void
	{
		$exception = InvalidCollectionIndexException::forMissingIndex('App\Collection\ItemCollection', 5);

		self::assertInstanceOf(InvalidCollectionIndexException::class, $exception);
		self::assertSame(
			'Index 5 does not exist in App\Collection\ItemCollection.',
			$exception->getMessage()
		);
		self::assertSame('base.domain', $exception->translation->group);
		self::assertSame('not_exist_in_collection', $exception->translation->key);
		self::assertSame(
			[
				'index' => 5,
				'class' => 'App\Collection\ItemCollection',
			],
			$exception->translation->params
		);
	}
}
