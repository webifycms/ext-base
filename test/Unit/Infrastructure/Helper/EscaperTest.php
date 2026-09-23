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

namespace Webify\Base\Test\Unit\Infrastructure\Helper;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Contract\EscaperInterface;
use Webify\Base\Infrastructure\Helper\Escaper;

/**
 * EscaperTest tests the default HTML escaper implementation.
 *
 * @internal
 */
#[CoversClass(Escaper::class)]
#[CoversMethod(Escaper::class, 'escape')]
final class EscaperTest extends TestCase
{
	/**
	 * Tests that the escaper implements the common contract.
	 */
	#[Test]
	public function testImplementsEscaperInterface(): void
	{
		self::assertInstanceOf(EscaperInterface::class, new Escaper());
	}

	/**
	 * Tests that HTML special characters are escaped, quotes included.
	 */
	#[Test]
	public function testEscapesHtmlSpecialCharacters(): void
	{
		$escaper = new Escaper();

		self::assertSame(
			'&lt;script&gt;alert(&quot;xss&quot;)&amp;&#039;done&#039;&lt;/script&gt;',
			$escaper->escape('<script>alert("xss")&\'done\'</script>')
		);
	}

	/**
	 * Tests that a plain string is returned unchanged.
	 */
	#[Test]
	public function testLeavesPlainStringUntouched(): void
	{
		$escaper = new Escaper();

		self::assertSame('Hello, WebifyCMS!', $escaper->escape('Hello, WebifyCMS!'));
	}
}
