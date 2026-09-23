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

namespace Webify\Base\Test\Unit\Infrastructure\Theme;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Exception\ThemeException;
use Webify\Base\Infrastructure\Theme\ThemeManifestValidator;

/**
 * ThemeManifestValidatorTest tests the functionality of the ThemeManifestValidator class.
 *
 * @internal
 */
#[CoversClass(ThemeManifestValidator::class)]
#[CoversMethod(ThemeManifestValidator::class, 'validate')]
#[UsesClass(ThemeException::class)]
final class ThemeManifestValidatorTest extends TestCase
{
	/**
	 * Test that a manifest with an id and name validates.
	 */
	#[Test]
	public function testValidateAcceptsValidManifest(): void
	{
		$data = ['id' => 'mytheme', 'name' => 'My Theme'];

		self::assertSame($data, $this->validator()->validate($data, '/path/to/theme.json'));
	}

	/**
	 * Test that a manifest without an id is invalid.
	 */
	#[Test]
	public function testValidateThrowsWhenIdMissing(): void
	{
		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->validator()->validate(['name' => 'My Theme'], '/path/to/theme.json');
	}

	/**
	 * Test that a manifest with an empty id is invalid.
	 */
	#[Test]
	public function testValidateThrowsWhenIdEmpty(): void
	{
		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->validator()->validate(['id' => '', 'name' => 'My Theme'], '/path/to/theme.json');
	}

	/**
	 * Test that a manifest with a non-string id is invalid.
	 */
	#[Test]
	public function testValidateThrowsWhenIdNotString(): void
	{
		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->validator()->validate(['id' => 42, 'name' => 'My Theme'], '/path/to/theme.json');
	}

	/**
	 * Test that a manifest without a name is invalid.
	 */
	#[Test]
	public function testValidateThrowsWhenNameMissing(): void
	{
		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->validator()->validate(['id' => 'mytheme'], '/path/to/theme.json');
	}

	/**
	 * Test that a manifest with an empty name is invalid.
	 */
	#[Test]
	public function testValidateThrowsWhenNameEmpty(): void
	{
		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->validator()->validate(['id' => 'mytheme', 'name' => ''], '/path/to/theme.json');
	}

	/**
	 * Test that a manifest with a non-string name is invalid.
	 */
	#[Test]
	public function testValidateThrowsWhenNameNotString(): void
	{
		$this->expectException(ThemeException::class);
		$this->expectExceptionMessageIsOrContains('Invalid manifest data from');

		$this->validator()->validate(['id' => 'mytheme', 'name' => false], '/path/to/theme.json');
	}

	/**
	 * Creates a theme manifest validator instance.
	 */
	private function validator(): ThemeManifestValidator
	{
		return new ThemeManifestValidator();
	}
}
