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

use League\Route\Router;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Contract\{BaseUrlInterface, UrlType};
use Webify\Base\Infrastructure\Service\{BaseUrl, Config, UrlGenerator};
use Webify\Base\Test\Unit\Infrastructure\Service\Example\ExampleResourcePath;

/**
 * UrlGeneratorTest tests the functionality of the UrlGenerator service.
 *
 * @internal
 */
#[CoversClass(UrlGenerator::class)]
#[CoversMethod(UrlGenerator::class, 'route')]
#[CoversMethod(UrlGenerator::class, 'path')]
#[CoversMethod(UrlGenerator::class, 'to')]
#[UsesClass(Router::class)]
#[UsesClass(BaseUrl::class)]
#[UsesClass(Config::class)]
#[UsesClass(ExampleResourcePath::class)]
final class UrlGeneratorTest extends TestCase
{
	/**
	 * The router used to build named routes.
	 */
	private Router $router;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->router = new Router();
		$this->router->map('GET', '/post/{id}', static fn (): string => '')->setName('post');
	}

	/**
	 * Test that a route generates a relative path.
	 */
	#[Test]
	public function testRouteGeneratesRelativePath(): void
	{
		$generator = new UrlGenerator($this->router, self::createStub(BaseUrlInterface::class));

		self::assertSame('/post/5', $generator->route('post', ['id' => 5], UrlType::Relative));
	}

	/**
	 * Test that an absolute URL uses the configured base URL with the default scheme.
	 */
	#[Test]
	public function testRouteGeneratesAbsoluteUrlWithDefaultScheme(): void
	{
		/** @var BaseUrlInterface&MockObject $baseUrl */
		$baseUrl = $this->createMock(BaseUrlInterface::class);
		$baseUrl->method('hasScheme')->willReturn(false);
		$baseUrl->expects(self::once())
			->method('toNative')
			->with('https')
			->willReturn('https://example.com')
		;

		$generator = new UrlGenerator($this->router, $baseUrl);

		self::assertSame('https://example.com/post/5', $generator->route('post', ['id' => 5], UrlType::Absolute));
	}

	/**
	 * Test that an absolute URL uses the base URL's scheme when it already has one.
	 */
	#[Test]
	public function testRouteGeneratesAbsoluteUrlWithExistingScheme(): void
	{
		/** @var BaseUrlInterface&MockObject $baseUrl */
		$baseUrl = $this->createMock(BaseUrlInterface::class);
		$baseUrl->method('hasScheme')->willReturn(true);
		$baseUrl->expects(self::once())
			->method('toNative')
			->with(null)
			->willReturn('http://example.com')
		;

		$generator = new UrlGenerator($this->router, $baseUrl);

		self::assertSame('http://example.com/post/5', $generator->route('post', ['id' => 5], UrlType::Absolute));
	}

	/**
	 * Test that a network-path URL delegates to toNative without a scheme.
	 */
	#[Test]
	public function testRouteGeneratesNetworkPathUrl(): void
	{
		/** @var BaseUrlInterface&MockObject $baseUrl */
		$baseUrl = $this->createMock(BaseUrlInterface::class);
		$baseUrl->expects(self::once())
			->method('toNative')
			->with('')
			->willReturn('//example.com')
		;

		$generator = new UrlGenerator($this->router, $baseUrl);

		self::assertSame('//example.com/post/5', $generator->route('post', ['id' => 5], UrlType::NetworkPath));
	}

	/**
	 * Test that path resolves a relative path.
	 */
	#[Test]
	public function testPathGeneratesRelativePath(): void
	{
		$generator = new UrlGenerator($this->router, self::createStub(BaseUrlInterface::class));

		self::assertSame('/foo?bar=1', $generator->path('/foo?bar=1', UrlType::Relative));
	}

	/**
	 * Test that path resolves an absolute URL.
	 */
	#[Test]
	public function testPathGeneratesAbsoluteUrl(): void
	{
		/** @var BaseUrlInterface&MockObject $baseUrl */
		$baseUrl = $this->createMock(BaseUrlInterface::class);
		$baseUrl->method('hasScheme')->willReturn(false);
		$baseUrl->expects(self::once())
			->method('toNative')
			->with('https')
			->willReturn('https://example.com')
		;

		$generator = new UrlGenerator($this->router, $baseUrl);

		self::assertSame('https://example.com/foo?bar=1', $generator->path('/foo?bar=1', UrlType::Absolute));
	}

	/**
	 * Test that to resolves a resource path to a relative path.
	 */
	#[Test]
	public function testToGeneratesRelativePathFromResourcePath(): void
	{
		$generator = new UrlGenerator($this->router, self::createStub(BaseUrlInterface::class));

		self::assertSame('/about', $generator->to(ExampleResourcePath::fromString('about'), UrlType::Relative));
	}

	/**
	 * Test that the default scheme is used only when the base URL has no scheme.
	 */
	#[Test]
	public function testDefaultSchemeIsUsedOnlyWhenBaseUrlLacksScheme(): void
	{
		$schemes = [];

		$baseUrl = self::createStub(BaseUrlInterface::class);
		$baseUrl->method('hasScheme')->willReturn(false);
		$baseUrl->method('toNative')
			->willReturnCallback(static function (?string $scheme) use (&$schemes): string {
				$schemes[] = $scheme;

				return '//example.com';
			})
		;

		$generator = new UrlGenerator($this->router, $baseUrl, 'http');

		$generator->route('post', ['id' => 5], UrlType::Absolute);

		self::assertSame(['http'], $schemes);
	}

	/**
	 * Test that a real base URL composes with generated paths without double slashes.
	 */
	#[Test]
	public function testRealBaseUrlDoesNotProduceDoubleSlashes(): void
	{
		$generator = new UrlGenerator(
			$this->router,
			BaseUrl::fromConfig(new Config(['baseUrl' => 'http://localhost']))
		);

		self::assertSame('http://localhost/post/5', $generator->route('post', ['id' => 5], UrlType::Absolute));
		self::assertSame('//localhost/post/5', $generator->route('post', ['id' => 5], UrlType::NetworkPath));
		self::assertSame('/post/5', $generator->route('post', ['id' => 5], UrlType::Relative));
		self::assertSame('http://localhost/foo?bar=1', $generator->path('/foo?bar=1', UrlType::Absolute));
	}
}
