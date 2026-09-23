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

namespace Webify\Base\Test\Unit\Infrastructure\Service;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Contract\{ExtensionInterface, TemplateMapOverridesInterface};
use Webify\Base\Infrastructure\Service\TemplateResolver;

/**
 * TemplateResolverTest tests the functionality of the TemplateResolver service.
 *
 * @internal
 */
#[CoversClass(TemplateResolver::class)]
#[CoversMethod(TemplateResolver::class, 'resolve')]
#[UsesClass(TemplatePath::class)]
final class TemplateResolverTest extends TestCase
{
	/**
	 * Test that resolve returns the override candidates followed by the extension fallback.
	 */
	#[Test]
	public function testResolveReturnsCandidatesThenExtensionFallback(): void
	{
		$templateMap = $this->createMock(TemplateMapOverridesInterface::class);
		$templateMap->expects(self::once())->method('forExtension')->with('blog')->willReturn([
			TemplatePath::fromString('/theme-a/tpl'),
			TemplatePath::fromString('/theme-b/tpl'),
		]);

		$extension = self::createStub(ExtensionInterface::class);
		$extension->method('getId')->willReturn('blog');
		$extension->method('getTemplatePath')->willReturn(TemplatePath::fromString('/ext/default/templates'));

		$resolver = new TemplateResolver($templateMap);

		self::assertSame(
			[
				'/theme-a/tpl/post',
				'/theme-b/tpl/post',
				'/ext/default/templates/post',
			],
			$resolver->resolve($extension, 'post')
		);
	}

	/**
	 * Test that a leading slash on the template name is trimmed.
	 */
	#[Test]
	public function testResolveTrimsLeadingSlash(): void
	{
		$templateMap = $this->createMock(TemplateMapOverridesInterface::class);
		$templateMap->expects(self::once())->method('forExtension')->with('blog')->willReturn([
			TemplatePath::fromString('/theme-a/tpl'),
		]);

		$extension = self::createStub(ExtensionInterface::class);
		$extension->method('getId')->willReturn('blog');
		$extension->method('getTemplatePath')->willReturn(TemplatePath::fromString('/ext/default/templates'));

		$resolver = new TemplateResolver($templateMap);

		self::assertSame(
			[
				'/theme-a/tpl/post',
				'/ext/default/templates/post',
			],
			$resolver->resolve($extension, '/post')
		);
	}

	/**
	 * Test that resolve falls back to the extension path when there are no candidates.
	 */
	#[Test]
	public function testResolveFallsBackToExtensionWhenNoCandidates(): void
	{
		$templateMap = $this->createMock(TemplateMapOverridesInterface::class);
		$templateMap->expects(self::once())->method('forExtension')->with('blog')->willReturn([]);

		$extension = self::createStub(ExtensionInterface::class);
		$extension->method('getId')->willReturn('blog');
		$extension->method('getTemplatePath')->willReturn(TemplatePath::fromString('/ext/default/templates'));

		$resolver = new TemplateResolver($templateMap);

		self::assertSame(
			['/ext/default/templates/post'],
			$resolver->resolve($extension, 'post')
		);
	}
}
