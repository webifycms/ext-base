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

namespace Webify\Base\Test\Unit\Infrastructure\Exception;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\Contract\Translation\ExceptionTranslation;
use Webify\Base\Infrastructure\Exception\ApplicationException;

/**
 * ApplicationExceptionTest tests the functionality of the ApplicationException class.
 *
 * @internal
 */
#[CoversClass(ApplicationException::class)]
#[CoversMethod(ApplicationException::class, 'basePathNotDefined')]
#[CoversMethod(ApplicationException::class, 'runtimePathNotDefined')]
#[CoversMethod(ApplicationException::class, 'runtimePathIsNotWritable')]
#[CoversMethod(ApplicationException::class, 'unableToCreateRuntimePaths')]
#[UsesClass(ExceptionTranslation::class)]
final class ApplicationExceptionTest extends TestCase
{
	/**
	 * Test the basePathNotDefined factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testBasePathNotDefined(): void
	{
		$exception = ApplicationException::basePathNotDefined();

		self::assertInstanceOf(ApplicationException::class, $exception);
		self::assertSame('Application base path not defined in the configurations.', $exception->getMessage());
		self::assertSame('base.config', $exception->translation->group);
		self::assertSame('bath_path_not_defined', $exception->translation->key);
	}

	/**
	 * Test the runtimePathNotDefined factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testRuntimePathNotDefined(): void
	{
		$exception = ApplicationException::runtimePathNotDefined();

		self::assertInstanceOf(ApplicationException::class, $exception);
		self::assertSame('Application runtime path not defined in the configurations.', $exception->getMessage());
		self::assertSame('base.config', $exception->translation->group);
		self::assertSame('runtime_path_not_defined', $exception->translation->key);
	}

	/**
	 * Test the runtimePathIsNotWritable factory method creates an exception with the path in the message.
	 */
	#[Test]
	public function testRuntimePathIsNotWritable(): void
	{
		$exception = ApplicationException::runtimePathIsNotWritable('/app/runtime');

		self::assertInstanceOf(ApplicationException::class, $exception);
		self::assertSame(
			'Application runtime path "/app/runtime" is not writable.',
			$exception->getMessage()
		);
		self::assertSame('base.config', $exception->translation->group);
		self::assertSame('runtime_path_is_not_writable', $exception->translation->key);
		self::assertSame(['path' => '/app/runtime'], $exception->translation->params);
	}

	/**
	 * Test the unableToCreateRuntimePaths factory method creates an exception with the correct message.
	 */
	#[Test]
	public function testUnableToCreateRuntimePaths(): void
	{
		$exception = ApplicationException::unableToCreateRuntimePaths();

		self::assertInstanceOf(ApplicationException::class, $exception);
		self::assertSame('Failed to create application runtime paths.', $exception->getMessage());
		self::assertSame('base.config', $exception->translation->group);
		self::assertSame('failed_to_create_runtime_paths', $exception->translation->key);
	}
}
