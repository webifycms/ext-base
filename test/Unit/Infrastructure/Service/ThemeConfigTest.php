<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Test\Unit\Infrastructure\Service;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Contract\ArraySearchHelper;
use Webify\Base\Infrastructure\Service\ThemeConfig;

/**
 * ThemeConfigTest tests the functionality of the ThemeConfig service class.
 *
 * @internal
 */
#[CoversClass(ThemeConfig::class)]
#[CoversMethod(ThemeConfig::class, 'has')]
#[CoversMethod(ThemeConfig::class, 'get')]
#[UsesClass(ArraySearchHelper::class)]
final class ThemeConfigTest extends TestCase
{
	/**
	 * The theme configuration under test.
	 */
	private ThemeConfig $config;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->config = new ThemeConfig([
			'name'     => 'Example Theme',
			'database' => [
				'host' => 'localhost',
				'port' => 3306,
			],
			'nullable' => null,
		]);
	}

	/**
	 * Test retrieving a nested value using dot notation.
	 */
	#[Test]
	public function testGetNestedKey(): void
	{
		self::assertSame('localhost', $this->config->get('database.host'));
		self::assertSame(3306, $this->config->get('database.port'));
	}

	/**
	 * Test that get returns the default value when the key does not exist.
	 */
	#[Test]
	public function testGetMissingKeyReturnsDefault(): void
	{
		self::assertNull($this->config->get('missing'));
		self::assertSame('fallback', $this->config->get('missing', 'fallback'));
	}

	/**
	 * Test that a key holding a null value exists and returns null.
	 */
	#[Test]
	public function testNullValueExists(): void
	{
		self::assertTrue($this->config->has('nullable'));
		self::assertNull($this->config->get('nullable'));
	}

	/**
	 * Test that has returns false for a non-existent key.
	 */
	#[Test]
	public function testHasReturnsFalseForMissingKey(): void
	{
		self::assertFalse($this->config->has('missing'));
	}

	/**
	 * Test that repeated lookups return the cached value.
	 */
	#[Test]
	public function testValuesAreCached(): void
	{
		self::assertSame('localhost', $this->config->get('database.host'));
		self::assertTrue($this->config->has('database.host'));
		self::assertSame('localhost', $this->config->get('database.host'));
	}
}
