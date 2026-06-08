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

namespace Webify\Base\Test\Unit\Infrastructure\Kernel;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application as ConsoleApplication;
use Webify\Base\Infrastructure\Kernel\Console;

/**
 * ConsoleKernelTest tests the functionality of the Console kernel.
 *
 * @internal
 */
#[CoversClass(Console::class)]
#[CoversMethod(Console::class, 'handle')]
final class ConsoleKernelTest extends TestCase
{
	/**
	 * Test that handle runs the console application and returns an exit code.
	 */
	#[Test]
	public function testHandleRunsConsoleApplication(): void
	{
		$consoleApp = $this->createMock(ConsoleApplication::class);
		$consoleApp->expects(self::once())->method('run')->willReturn(0);

		$kernel   = new Console($consoleApp);
		$exitCode = $kernel->handle();

		self::assertSame(0, $exitCode);
	}

	/**
	 * Test that handle returns a non-zero exit code on error.
	 */
	#[Test]
	public function testHandleReturnsNonZeroOnError(): void
	{
		$consoleApp = $this->createMock(ConsoleApplication::class);
		$consoleApp->expects(self::once())->method('run')->willReturn(1);

		$kernel   = new Console($consoleApp);
		$exitCode = $kernel->handle();

		self::assertSame(1, $exitCode);
	}

	/**
	 * Test that handle works with a fully functional Symfony console application.
	 */
	#[Test]
	public function testHandleWithRealConsoleApplication(): void
	{
		$consoleApp = new ConsoleApplication('test', '1.0.0');
		$consoleApp->setAutoExit(false);

		$kernel   = new Console($consoleApp);
		$exitCode = $kernel->handle();

		self::assertIsInt($exitCode);
	}
}
