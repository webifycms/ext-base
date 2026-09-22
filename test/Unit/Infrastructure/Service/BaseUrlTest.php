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
use Webify\Base\Infrastructure\Exception\BaseUrlConfigurationException;
use Webify\Base\Infrastructure\Service\{BaseUrl, Config};

/**
 * BaseUrlTest tests the functionality of the BaseUrl service.
 *
 * @internal
 */
#[CoversClass(BaseUrl::class)]
#[CoversMethod(BaseUrl::class, 'fromConfig')]
#[CoversMethod(BaseUrl::class, 'toNative')]
#[CoversMethod(BaseUrl::class, 'hasScheme')]
#[CoversMethod(BaseUrl::class, 'getScheme')]
#[CoversMethod(BaseUrl::class, 'getHost')]
#[CoversMethod(BaseUrl::class, 'getPathPrefix')]
#[UsesClass(Config::class)]
#[UsesClass(BaseUrlConfigurationException::class)]
final class BaseUrlTest extends TestCase
{
	/**
	 * Test that an empty base URL is treated as not configured.
	 */
	#[Test]
	public function testEmptyBaseUrlIsNotConfigured(): void
	{
		$baseUrl = BaseUrl::fromConfig(new Config(['baseUrl' => '']));

		self::assertFalse($baseUrl->hasScheme());
		self::assertSame('', $baseUrl->getHost());
		self::assertSame('', $baseUrl->getPathPrefix());

		$this->expectException(BaseUrlConfigurationException::class);
		$this->expectExceptionMessageMatches('/no base URL is configured/');

		$baseUrl->toNative();
	}

	/**
	 * Test that a bare host uses the configured default scheme.
	 */
	#[Test]
	public function testBareHostUsesDefaultScheme(): void
	{
		$baseUrl = BaseUrl::fromConfig(new Config([
			'baseUrl'           => 'example.com',
			'defaultUrlScheme'  => 'http',
		]));

		self::assertSame('example.com', $baseUrl->getHost());
		self::assertTrue($baseUrl->hasScheme());
		self::assertSame('http', $baseUrl->getScheme());
		self::assertSame('http://example.com', $baseUrl->toNative('http'));
	}

	/**
	 * Test that a protocol-relative base URL still resolves to the default scheme.
	 */
	#[Test]
	public function testProtocolRelativeBaseUrlUsesDefaultScheme(): void
	{
		$baseUrl = BaseUrl::fromConfig(new Config([
			'baseUrl'          => '//example.com',
			'defaultUrlScheme' => 'http',
		]));

		self::assertSame('example.com', $baseUrl->getHost());
		self::assertTrue($baseUrl->hasScheme());
		self::assertSame('http', $baseUrl->getScheme());
		self::assertSame('http://example.com', $baseUrl->toNative());
	}

	/**
	 * Test that an invalid base URL throws a configuration exception.
	 */
	#[Test]
	public function testInvalidBaseUrlThrowsConfigurationException(): void
	{
		$this->expectException(BaseUrlConfigurationException::class);

		BaseUrl::fromConfig(new Config(['baseUrl' => 'http://']));
	}

	/**
	 * Test that an invalid base URL exception has a translation payload.
	 */
	#[Test]
	public function testInvalidBaseUrlHasTranslation(): void
	{
		try {
			BaseUrl::fromConfig(new Config(['baseUrl' => 'http://']));
			self::fail('Expected a BaseUrlConfigurationException to be thrown.');
		} catch (BaseUrlConfigurationException $exception) {
			self::assertSame('base.configuration', $exception->translation->group);
			self::assertSame('invalid_base_url', $exception->translation->key);
		}
	}

	/**
	 * Test parsing a full URL with scheme, host, port and path prefix.
	 */
	#[Test]
	public function testFullUrlWithSchemeHostPortAndPath(): void
	{
		$baseUrl = BaseUrl::fromConfig(new Config(['baseUrl' => 'https://example.com:8080/blog']));

		self::assertSame('https', $baseUrl->getScheme());
		self::assertSame('example.com', $baseUrl->getHost());
		self::assertSame('/blog', $baseUrl->getPathPrefix());
		self::assertSame('https://example.com:8080/blog', $baseUrl->toNative());
	}

	/**
	 * Test that a port is formatted into the native URL.
	 */
	#[Test]
	public function testPortIsFormattedIntoNativeUrl(): void
	{
		$baseUrl = BaseUrl::fromConfig(new Config(['baseUrl' => 'http://example.com:8080']));

		self::assertSame('http://example.com:8080', $baseUrl->toNative());
	}

	/**
	 * Test that an explicit scheme overrides the stored scheme when generating a URL.
	 */
	#[Test]
	public function testExplicitSchemeOverridesStoredScheme(): void
	{
		$baseUrl = BaseUrl::fromConfig(new Config(['baseUrl' => 'http://example.com']));

		self::assertSame('http', $baseUrl->getScheme());
		self::assertSame('https://example.com', $baseUrl->toNative('https'));
	}

	/**
	 * Test that an empty effective scheme yields a protocol-relative URL.
	 */
	#[Test]
	public function testEmptySchemeYieldsNetworkPath(): void
	{
		$baseUrl = BaseUrl::fromConfig(new Config(['baseUrl' => 'https://example.com:8080/blog']));

		self::assertSame('//example.com:8080/blog', $baseUrl->toNative(''));
	}
}
