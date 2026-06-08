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

namespace Webify\Base\Test\Unit\Infrastructure\Environment;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Environment\{Environment, Type};
use Webify\Base\Infrastructure\Exception\ApplicationEnvironmentException;

/**
 * EnvironmentTest tests the functionality of the Environment class.
 *
 * @internal
 */
#[CoversClass(Environment::class)]
#[CoversMethod(Environment::class, 'prepare')]
#[CoversMethod(Environment::class, 'isProduction')]
#[CoversMethod(Environment::class, 'isDevelopment')]
#[CoversMethod(Environment::class, 'isDebugEnabled')]
#[UsesClass(Type::class)]
#[UsesClass(ApplicationEnvironmentException::class)]
final class EnvironmentTest extends TestCase
{
	/**
	 * Test that prepare returns a production environment when configured accordingly.
	 */
	#[Test]
	public function testPrepareWithProductionEnvironment(): void
	{
		$config = $this->createMock(ConfigInterface::class);
		$config->expects(self::once())->method('has')->with('environment')->willReturn(true);
		$config->expects(self::exactly(2))->method('get')->willReturnMap([
			['environment', null, 'production'],
			['debug', false, true],
		]);

		$environment = Environment::prepare($config);

		self::assertTrue($environment->isProduction());
		self::assertFalse($environment->isDevelopment());
		self::assertTrue($environment->isDebugEnabled());
	}

	/**
	 * Test that prepare returns a development environment when configured accordingly.
	 */
	#[Test]
	public function testPrepareWithDevelopmentEnvironment(): void
	{
		$config = $this->createMock(ConfigInterface::class);
		$config->expects(self::once())->method('has')->with('environment')->willReturn(true);
		$config->expects(self::exactly(2))->method('get')->willReturnMap([
			['environment', null, 'development'],
			['debug', false, false],
		]);

		$environment = Environment::prepare($config);

		self::assertTrue($environment->isDevelopment());
		self::assertFalse($environment->isProduction());
		self::assertFalse($environment->isDebugEnabled());
	}

	/**
	 * Test that prepare throws an exception when the environment is not defined in config.
	 */
	#[Test]
	public function testPrepareThrowsExceptionWhenEnvironmentNotDefined(): void
	{
		$this->expectException(ApplicationEnvironmentException::class);
		$this->expectExceptionMessage('Application environment not defined in the configurations.');

		$config = $this->createMock(ConfigInterface::class);
		$config->expects(self::once())->method('has')->with('environment')->willReturn(false);

		Environment::prepare($config);
	}

	/**
	 * Test that prepare uses the default debug value of false when not specified.
	 */
	#[Test]
	public function testPrepareWithDefaultDebugValue(): void
	{
		$config = $this->createMock(ConfigInterface::class);
		$config->expects(self::once())->method('has')->with('environment')->willReturn(true);
		$config->expects(self::exactly(2))->method('get')->willReturnMap([
			['environment', null, 'production'],
			['debug', false, false],
		]);

		$environment = Environment::prepare($config);

		self::assertFalse($environment->isDebugEnabled());
	}
}
