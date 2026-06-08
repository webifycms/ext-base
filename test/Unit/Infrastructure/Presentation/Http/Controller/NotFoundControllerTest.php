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
use Webify\Base\Infrastructure\Presentation\Http\Controller\NotFoundController;

/**
 * NotFoundControllerTest tests the functionality of the NotFoundController.
 *
 * @internal
 */
#[CoversClass(NotFoundController::class)]
#[CoversMethod(NotFoundController::class, '__invoke')]
final class NotFoundControllerTest extends TestCase
{
	/**
	 * Test that the not-found controller returns a 404 status code.
	 */
	#[Test]
	public function testInvokeReturns404Response(): void
	{
		$controller = new NotFoundController(new Psr17Factory());
		$request    = self::createStub(ServerRequestInterface::class);
		$response   = $controller->__invoke($request);

		self::assertSame(404, $response->getStatusCode());
	}

	/**
	 * Test that the not-found controller returns the expected body content.
	 */
	#[Test]
	public function testInvokeReturnsNotFoundBody(): void
	{
		$controller = new NotFoundController(new Psr17Factory());
		$request    = self::createStub(ServerRequestInterface::class);
		$response   = $controller->__invoke($request);

		self::assertSame('404 — Page Not Found', (string) $response->getBody());
	}

	/**
	 * Test that the not-found controller returns a plain text content type.
	 */
	#[Test]
	public function testInvokeReturnsTextPlainContentType(): void
	{
		$controller = new NotFoundController(new Psr17Factory());
		$request    = self::createStub(ServerRequestInterface::class);
		$response   = $controller->__invoke($request);

		self::assertSame('text/plain; charset=utf-8', $response->getHeaderLine('Content-Type'));
	}
}
