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

namespace Webify\Base\Test\Unit\Infrastructure\Container\Definitions;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\Router;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use PHPUnit\Framework\Attributes\{CoversClass, Test};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Webify\Base\Contract\EscaperInterface;
use Webify\Base\Infrastructure\Container\Definitions\HttpDefinitions;
use Webify\Base\Infrastructure\Contract\DefinitionsInterface;

/**
 * HttpDefinitionsTest tests the HTTP stack definitions group.
 *
 * @internal
 */
#[CoversClass(HttpDefinitions::class)]
final class HttpDefinitionsTest extends TestCase
{
	/**
	 * Tests that the group implements the definitions contract.
	 */
	#[Test]
	public function testImplementsDefinitionsInterface(): void
	{
		self::assertInstanceOf(DefinitionsInterface::class, new HttpDefinitions());
	}

	/**
	 * Tests that the HTTP stack is wired.
	 */
	#[Test]
	public function testGetDefinitionsWiresHttpStack(): void
	{
		$definitions = HttpDefinitions::getDefinitions();

		self::assertArrayHasKey(EscaperInterface::class, $definitions);
		self::assertArrayHasKey(RequestFactoryInterface::class, $definitions);
		self::assertArrayHasKey(ServerRequestCreatorInterface::class, $definitions);
		self::assertArrayHasKey(Router::class, $definitions);
		self::assertArrayHasKey(EmitterInterface::class, $definitions);
		self::assertArrayHasKey('httpKernel', $definitions);
	}
}
