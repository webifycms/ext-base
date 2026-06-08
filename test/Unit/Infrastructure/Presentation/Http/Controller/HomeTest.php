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

namespace Webify\Base\Test\Unit\Infrastructure\Presentation\Http\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Webify\Base\Infrastructure\Presentation\Http\Controller\Home;

/**
 * HomeTest tests the functionality of the Home controller.
 *
 * @internal
 */
#[CoversClass(Home::class)]
#[CoversMethod(Home::class, '__invoke')]
final class HomeTest extends TestCase
{
	/**
	 * Test that the home controller returns a 200 status code.
	 */
	#[Test]
	public function testInvokeReturns200Response(): void
	{
		$controller = new Home(new Psr17Factory());
		$request    = self::createStub(ServerRequestInterface::class);
		$response   = $controller->__invoke($request);

		self::assertSame(200, $response->getStatusCode());
	}

	/**
	 * Test that the home controller returns the expected body content.
	 */
	#[Test]
	public function testInvokeReturnsHelloWorldBody(): void
	{
		$controller = new Home(new Psr17Factory());
		$request    = self::createStub(ServerRequestInterface::class);
		$response   = $controller->__invoke($request);

		self::assertSame('Hello, world!', (string) $response->getBody());
	}

	/**
	 * Test that the home controller returns a plain text content type.
	 */
	#[Test]
	public function testInvokeReturnsTextPlainContentType(): void
	{
		$controller = new Home(new Psr17Factory());
		$request    = self::createStub(ServerRequestInterface::class);
		$response   = $controller->__invoke($request);

		self::assertSame('text/plain', $response->getHeaderLine('Content-Type'));
	}
}
