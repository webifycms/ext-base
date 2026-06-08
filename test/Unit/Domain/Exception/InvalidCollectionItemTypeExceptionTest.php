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
use Webify\Base\Domain\Exception\InvalidCollectionItemTypeException;

/**
 * InvalidCollectionItemTypeExceptionTest tests the functionality of the InvalidCollectionItemTypeException class.
 *
 * @internal
 */
#[CoversClass(InvalidCollectionItemTypeException::class)]
#[CoversMethod(InvalidCollectionItemTypeException::class, 'forInvalidItemType')]
#[UsesClass(ExceptionTranslation::class)]
final class InvalidCollectionItemTypeExceptionTest extends TestCase
{
	/**
	 * Test the forInvalidItemType factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testForInvalidItemType(): void
	{
		$exception = InvalidCollectionItemTypeException::forInvalidItemType(
			'App\Collection\ItemCollection',
			'App\Entity\Item',
			'stdClass'
		);

		self::assertInstanceOf(InvalidCollectionItemTypeException::class, $exception);
		self::assertSame(
			'App\Collection\ItemCollection only accepts items of type App\Entity\Item, got stdClass.',
			$exception->getMessage()
		);
		self::assertSame('base.domain', $exception->translation->group);
		self::assertSame('invalid_type_of_collection', $exception->translation->key);
		self::assertSame(
			[
				'class'        => 'App\Collection\ItemCollection',
				'expectedType' => 'App\Entity\Item',
				'givenType'    => 'stdClass',
			],
			$exception->translation->params
		);
	}
}
