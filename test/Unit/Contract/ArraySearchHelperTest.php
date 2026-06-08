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

namespace Webify\Base\Test\Unit\Contract;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Contract\ArraySearchHelper;

/**
 * ArraySearchHelperTest tests the functionality of the ArraySearchHelper trait.
 *
 * @internal
 */
#[CoversClass(ArraySearchHelper::class)]
#[CoversMethod(ArraySearchHelper::class, 'search')]
final class ArraySearchHelperTest extends TestCase
{
	/**
	 * @var mixed anonymous class instance with the ArraySearchHelper trait
	 */
	private mixed $helper;

	/**
	 * {@inheritDoc}
	 */
	protected function setUp(): void
	{
		$this->helper = new class {
			use ArraySearchHelper;
		};
	}

	/**
	 * Test searching for a value using a simple (non-dotted) key.
	 */
	#[Test]
	public function testSearchWithSimpleKey(): void
	{
		$array = ['name' => 'John', 'age' => 30];

		self::assertSame('John', $this->helper->search('name', $array));
		self::assertSame(30, $this->helper->search('age', $array));
	}

	/**
	 * Test searching for a nested value using dot notation.
	 */
	#[Test]
	public function testSearchWithDotNotation(): void
	{
		$array = [
			'database' => [
				'host' => 'localhost',
				'port' => 3306,
			],
			'app'      => [
				'name'  => 'WebifyCMS',
				'debug' => true,
			],
		];

		self::assertSame('localhost', $this->helper->search('database.host', $array));
		self::assertSame(3306, $this->helper->search('database.port', $array));
		self::assertSame('WebifyCMS', $this->helper->search('app.name', $array));
		self::assertTrue($this->helper->search('app.debug', $array));
	}

	/**
	 * Test searching for a key that does not exist in the array.
	 */
	#[Test]
	public function testSearchWithNonExistentKey(): void
	{
		$array = ['name' => 'John'];

		self::assertNull($this->helper->search('age', $array));
	}

	/**
	 * Test searching for a nested key that does not exist in the array.
	 */
	#[Test]
	public function testSearchWithNonExistentNestedKey(): void
	{
		$array = [
			'database' => [
				'host' => 'localhost',
			],
		];

		self::assertNull($this->helper->search('database.password', $array));
	}

	/**
	 * Test searching returns a default value when the key does not exist.
	 */
	#[Test]
	public function testSearchWithDefaultValue(): void
	{
		$array = ['name' => 'John'];

		self::assertSame('default', $this->helper->search('age', $array, 'default'));
	}

	/**
	 * Test searching with an empty path returns null.
	 */
	#[Test]
	public function testSearchWithEmptyPath(): void
	{
		$array = ['name' => 'John'];

		self::assertNull($this->helper->search('', $array));
	}

	/**
	 * Test searching with a deeply nested dot-notated path.
	 */
	#[Test]
	public function testSearchWithDeeplyNestedArray(): void
	{
		$array = [
			'a' => [
				'b' => [
					'c' => [
						'd' => 'deep-value',
					],
				],
			],
		];

		self::assertSame('deep-value', $this->helper->search('a.b.c.d', $array));
	}

	/**
	 * Test searching returns null when the path goes beyond the existing depth.
	 */
	#[Test]
	public function testSearchWithPartialPathReturnsNull(): void
	{
		$array = [
			'a' => [
				'b' => 'value',
			],
		];

		self::assertNull($this->helper->search('a.b.c', $array));
	}

	/**
	 * Test searching when an intermediate value is not an array returns null.
	 */
	#[Test]
	public function testSearchWhenIntermediateIsNotArray(): void
	{
		$array = [
			'a' => 'scalar',
			'b' => [
				'c' => 'value',
			],
		];

		self::assertNull($this->helper->search('a.b', $array));
		self::assertSame('value', $this->helper->search('b.c', $array));
	}

	/**
	 * Test searching with numeric string keys.
	 */
	#[Test]
	public function testSearchWithNumericKeys(): void
	{
		$array = [
			'0' => 'first',
			'1' => 'second',
		];

		self::assertSame('first', $this->helper->search('0', $array));
		self::assertSame('second', $this->helper->search('1', $array));
	}
}
