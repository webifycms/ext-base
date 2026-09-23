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
use Psr\Log\LoggerInterface;
use Webify\Base\Infrastructure\Container\Definitions\LoggingDefinitions;
use Webify\Base\Infrastructure\Contract\DefinitionsInterface;

/**
 * LoggingDefinitionsTest tests the logging definitions group.
 *
 * @internal
 */
#[CoversClass(LoggingDefinitions::class)]
final class LoggingDefinitionsTest extends TestCase
{
	/**
	 * Tests that the group implements the definitions contract.
	 */
	#[Test]
	public function testImplementsDefinitionsInterface(): void
	{
		self::assertInstanceOf(DefinitionsInterface::class, new LoggingDefinitions());
	}

	/**
	 * Tests that the logger is wired.
	 */
	#[Test]
	public function testGetDefinitionsWiresLogger(): void
	{
		$definitions = LoggingDefinitions::getDefinitions();

		self::assertArrayHasKey(LoggerInterface::class, $definitions);
	}
}
