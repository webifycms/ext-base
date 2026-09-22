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

use PHPUnit\Framework\Attributes\{CoversClass, Test};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application as ConsoleApplication;
use Webify\Base\Infrastructure\Container\Definitions\ConsoleDefinitions;
use Webify\Base\Infrastructure\Contract\DefinitionsInterface;

/**
 * ConsoleDefinitionsTest tests the console definitions group.
 *
 * @internal
 */
#[CoversClass(ConsoleDefinitions::class)]
final class ConsoleDefinitionsTest extends TestCase
{
	/**
	 * Tests that the group implements the definitions contract.
	 */
	#[Test]
	public function testImplementsDefinitionsInterface(): void
	{
		self::assertInstanceOf(DefinitionsInterface::class, new ConsoleDefinitions());
	}

	/**
	 * Tests that the console application and kernel are wired.
	 */
	#[Test]
	public function testGetDefinitionsWiresConsoleStack(): void
	{
		$definitions = ConsoleDefinitions::getDefinitions();

		self::assertArrayHasKey(ConsoleApplication::class, $definitions);
		self::assertArrayHasKey('consoleKernel', $definitions);
	}
}
