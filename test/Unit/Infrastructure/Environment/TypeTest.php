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

namespace Webify\Base\Test\Unit\Infrastructure\Environment;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Environment\Type;

/**
 * TypeTest tests the functionality of the Type enum.
 *
 * @internal
 */
#[CoversClass(Type::class)]
#[CoversMethod(Type::class, 'isProduction')]
#[CoversMethod(Type::class, 'isDevelopment')]
final class TypeTest extends TestCase
{
	/**
	 * Test the Production enum case returns the correct value and comparison methods.
	 */
	#[Test]
	public function testProductionType(): void
	{
		$type = Type::Production;

		self::assertSame('production', $type->value);
		self::assertTrue($type->isProduction());
		self::assertFalse($type->isDevelopment());
	}

	/**
	 * Test the Development enum case returns the correct value and comparison methods.
	 */
	#[Test]
	public function testDevelopmentType(): void
	{
		$type = Type::Development;

		self::assertSame('development', $type->value);
		self::assertTrue($type->isDevelopment());
		self::assertFalse($type->isProduction());
	}

	/**
	 * Test the from method converts a string to the correct enum case.
	 */
	#[Test]
	public function testFromString(): void
	{
		$production  = Type::from('production');
		$development = Type::from('development');

		self::assertSame(Type::Production, $production);
		self::assertSame(Type::Development, $development);
	}

	/**
	 * Test the tryFrom method returns null for unrecognized strings.
	 */
	#[Test]
	public function testTryFrom(): void
	{
		self::assertSame(Type::Production, Type::tryFrom('production'));
		self::assertSame(Type::Development, Type::tryFrom('development'));
		self::assertNull(Type::tryFrom('staging'));
	}
}
