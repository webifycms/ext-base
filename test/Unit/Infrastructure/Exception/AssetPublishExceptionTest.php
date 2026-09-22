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

namespace Webify\Base\Test\Unit\Infrastructure\Exception;

use PHPUnit\Framework\Attributes\{CoversClass, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Webify\Base\Contract\Exception\TranslatableExceptionInterface;
use Webify\Base\Contract\Translation\ExceptionTranslation;
use Webify\Base\Infrastructure\Exception\AssetPublishException;

/**
 * AssetPublishExceptionTest tests the functionality of the AssetPublishException class.
 *
 * @internal
 */
#[CoversClass(AssetPublishException::class)]
#[CoversClass(ExceptionTranslation::class)]
#[UsesClass(TranslatableExceptionInterface::class)]
final class AssetPublishExceptionTest extends TestCase
{
	/**
	 * Test that the exception implements the TranslatableExceptionInterface.
	 */
	#[Test]
	public function testImplementsTranslatableExceptionInterface(): void
	{
		$exception = AssetPublishException::sourceNotFound('/some/path');

		self::assertInstanceOf(TranslatableExceptionInterface::class, $exception);
	}

	/**
	 * Test that the translation DTO is populated for every factory method.
	 */
	#[Test]
	public function testTranslationIsExceptionTranslation(): void
	{
		$exception = AssetPublishException::sourceNotFound('/some/path');

		self::assertInstanceOf(ExceptionTranslation::class, $exception->translation);
		self::assertSame('base.asset_publish', $exception->translation->group);
		self::assertSame('source_not_found', $exception->translation->key);
		self::assertSame(['path' => '/some/path'], $exception->translation->params);
	}

	/**
	 * Test that sourceNotFound contains the path and has no previous exception.
	 */
	#[Test]
	public function testSourceNotFound(): void
	{
		$path      = '/var/www/public/assets/abc123';
		$exception = AssetPublishException::sourceNotFound($path);

		self::assertStringContainsString($path, $exception->getMessage());
		self::assertNull($exception->getPrevious());
		self::assertSame('base.asset_publish', $exception->translation->group);
		self::assertSame('source_not_found', $exception->translation->key);
		self::assertSame(['path' => $path], $exception->translation->params);
	}

	/**
	 * Test that targetNotWritable contains the path and chains the previous exception.
	 */
	#[Test]
	public function testTargetNotWritable(): void
	{
		$path      = '/var/www/public/assets/abc123';
		$previous  = new RuntimeException('previous failure');
		$exception = AssetPublishException::targetNotWritable($path, $previous);

		self::assertStringContainsString($path, $exception->getMessage());
		self::assertMatchesRegularExpression('/not writable/', $exception->getMessage());
		self::assertSame($previous, $exception->getPrevious());
		self::assertSame('base.asset_publish', $exception->translation->group);
		self::assertSame('target_not_writable', $exception->translation->key);
		self::assertSame(['path' => $path], $exception->translation->params);
	}

	/**
	 * Test that copyFailed contains the source and target paths and chains the previous exception.
	 */
	#[Test]
	public function testCopyFailed(): void
	{
		$source    = '/var/www/extensions/ext/src/dist';
		$target    = '/var/www/public/assets/abc123';
		$previous  = new RuntimeException('previous failure');
		$exception = AssetPublishException::copyFailed($source, $target, $previous);

		self::assertStringContainsString('Failed to copy asset', $exception->getMessage());
		self::assertStringContainsString($source, $exception->getMessage());
		self::assertStringContainsString($target, $exception->getMessage());
		self::assertSame($previous, $exception->getPrevious());
		self::assertSame('base.asset_publish', $exception->translation->group);
		self::assertSame('copy_failed', $exception->translation->key);
		self::assertSame(['source' => $source, 'target' => $target], $exception->translation->params);
	}
}
