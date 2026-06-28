<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Test\Unit\Infrastructure\Service;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Contract\ArraySearchHelper;
use Webify\Base\Infrastructure\Service\Config;

/**
 * ConfigTest tests the functionality of the Config service class.
 *
 * @internal
 */
#[CoversClass(Config::class)]
#[CoversMethod(Config::class, 'has')]
#[CoversMethod(Config::class, 'get')]
#[UsesClass(ArraySearchHelper::class)]
final class ConfigTest extends TestCase
{
	/**
	 * Test retrieving a simple (non-dotted) key from the config.
	 */
	#[Test]
	public function testGetSimpleKey(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
			'name'        => 'WebifyCMS',
		]);

		self::assertSame('WebifyCMS', $config->get('name'));
	}

	/**
	 * Test retrieving a nested value using dot notation.
	 */
	#[Test]
	public function testGetWithDotNotation(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
			'database'    => [
				'host' => 'localhost',
				'port' => 3306,
			],
		]);

		self::assertSame('localhost', $config->get('database.host'));
		self::assertSame(3306, $config->get('database.port'));
	}

	/**
	 * Test that get returns the default value when the key does not exist.
	 */
	#[Test]
	public function testGetReturnsDefaultWhenKeyNotFound(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		self::assertNull($config->get('non_existent'));
		self::assertSame('default', $config->get('non_existent', 'default'));
	}

	/**
	 * Test that has returns true for an existing key.
	 */
	#[Test]
	public function testHasReturnsTrueForExistingKey(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
			'debug'       => true,
		]);

		self::assertTrue($config->has('debug'));
	}

	/**
	 * Test that has returns false for a non-existent key.
	 */
	#[Test]
	public function testHasReturnsFalseForNonExistentKey(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		self::assertFalse($config->has('non_existent'));
	}

	/**
	 * Test that has works with dot notation for nested keys.
	 */
	#[Test]
	public function testHasWithDotNotation(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
			'database'    => [
				'host' => 'localhost',
			],
		]);

		self::assertTrue($config->has('database.host'));
		self::assertFalse($config->has('database.password'));
	}

	/**
	 * Test that get returns the cached result after has is called.
	 */
	#[Test]
	public function testGetCachesResultAfterHas(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
			'debug'       => true,
		]);

		self::assertTrue($config->has('debug'));
		self::assertTrue($config->get('debug'));
	}

	/**
	 * Test that Config implements the ConfigInterface.
	 */
	#[Test]
	public function testImplementsConfigInterface(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		self::assertInstanceOf(ConfigInterface::class, $config);
	}

	/**
	 * Test that path properties have trailing slashes trimmed.
	 */
	#[Test]
	public function testPathPropertiesAreTrimmed(): void
	{
		$config = new Config([
			'basePath'    => '/app/',
			'runtimePath' => '/app/runtime/',
			'configPath'  => '/app/config/',
		]);

		self::assertSame('/app', $config->basePath);
		self::assertSame('/app/runtime', $config->runtimePath);
		self::assertSame('/app/config', $config->configPath);
	}

	/**
	 * Test that cachePath is derived from runtimePath.
	 */
	#[Test]
	public function testCachePathProperty(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		self::assertSame('/app/runtime/cache', $config->cachePath);
	}

	/**
	 * Test that logPath is derived from runtimePath.
	 */
	#[Test]
	public function testLogPathProperty(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		self::assertSame('/app/runtime/log', $config->logPath);
	}

	/**
	 * Test that baseUrl returns the configured value when present.
	 */
	#[Test]
	public function testBaseUrlReturnsConfiguredValue(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
			'baseUrl'     => 'https://example.com',
		]);

		self::assertSame('https://example.com', $config->baseUrl);
	}

	/**
	 * Test that baseUrl returns an empty string when not configured.
	 */
	#[Test]
	public function testBaseUrlReturnsEmptyStringWhenNotConfigured(): void
	{
		$config = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		self::assertSame('', $config->baseUrl);
	}

	/**
	 * Test that with() returns a new instance with the merged configuration.
	 */
	#[Test]
	public function testWithReturnsNewInstanceWithMergedConfig(): void
	{
		$original = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		$enriched = $original->with(['baseUrl' => 'https://example.com']);

		self::assertNotSame($original, $enriched);
		self::assertSame('https://example.com', $enriched->baseUrl);
		self::assertSame($original->basePath, $enriched->basePath);
	}

	/**
	 * Test that with() does not mutate the original config instance.
	 */
	#[Test]
	public function testWithDoesNotMutateOriginalConfig(): void
	{
		$original = new Config([
			'basePath'    => '/app',
			'runtimePath' => '/app/runtime',
			'configPath'  => '/app/config',
		]);

		$original->with(['baseUrl' => 'https://example.com']);

		self::assertSame('', $original->baseUrl);
	}
}
