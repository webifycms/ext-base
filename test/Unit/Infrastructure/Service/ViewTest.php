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

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Contract\{
	AssetsPipelineInterface,
	ExtensionInterface,
	TemplateRendererInterface,
	UrlGeneratorInterface,
	UrlType,
};
use Webify\Base\Infrastructure\Service\View;

/**
 * ViewTest tests the functionality of the View service.
 *
 * @internal
 */
#[CoversClass(View::class)]
#[CoversMethod(View::class, 'render')]
#[CoversMethod(View::class, 'url')]
#[CoversMethod(View::class, 'path')]
#[CoversMethod(View::class, 'asset')]
final class ViewTest extends TestCase
{
	/**
	 * Test that render passes the view instance in the data to the renderer.
	 */
	#[Test]
	public function testRenderAddsViewToData(): void
	{
		/** @var MockObject&TemplateRendererInterface $renderer */
		$renderer = $this->createMock(TemplateRendererInterface::class);
		$renderer->expects(self::once())
			->method('render')
			->with(
				'post/view',
				self::callback(static fn (array $data): bool => $data['view'] instanceof View),
				null
			)
			->willReturn('<p>Hello</p>')
		;

		$view = new View(
			self::createStub(UrlGeneratorInterface::class),
			self::createStub(AssetsPipelineInterface::class),
			$renderer
		);

		self::assertSame('<p>Hello</p>', $view->render('post/view', ['title' => 'Hello']));
	}

	/**
	 * Test that render passes the current extension to the renderer.
	 */
	#[Test]
	public function testRenderPassesExtensionToRenderer(): void
	{
		/** @var ExtensionInterface&MockObject $extension */
		$extension = self::createStub(ExtensionInterface::class);

		/** @var MockObject&TemplateRendererInterface $renderer */
		$renderer = $this->createMock(TemplateRendererInterface::class);
		$renderer->expects(self::once())
			->method('render')
			->with('post/view', self::anything(), $extension)
			->willReturn('')
		;

		$view = new View(
			self::createStub(UrlGeneratorInterface::class),
			self::createStub(AssetsPipelineInterface::class),
			$renderer
		);
		$view->extension = $extension;

		$view->render('post/view');
	}

	/**
	 * Test that render sets the layout when provided.
	 */
	#[Test]
	public function testRenderSetsLayoutWhenProvided(): void
	{
		/** @var MockObject&TemplateRendererInterface $renderer */
		$renderer = self::createStub(TemplateRendererInterface::class);
		$renderer->method('render')->willReturn('');

		$view = new View(
			self::createStub(UrlGeneratorInterface::class),
			self::createStub(AssetsPipelineInterface::class),
			$renderer
		);

		$view->render('post/view', [], 'layouts/main');

		self::assertSame('layouts/main', $view->layout);
	}

	/**
	 * Test that render leaves the layout unchanged when not provided.
	 */
	#[Test]
	public function testRenderLeavesLayoutUnchangedWhenNull(): void
	{
		/** @var MockObject&TemplateRendererInterface $renderer */
		$renderer = self::createStub(TemplateRendererInterface::class);
		$renderer->method('render')->willReturn('');

		$view = new View(
			self::createStub(UrlGeneratorInterface::class),
			self::createStub(AssetsPipelineInterface::class),
			$renderer
		);
		$view->layout = 'layouts/base';

		$view->render('post/view', [], null);

		self::assertSame('layouts/base', $view->layout);
	}

	/**
	 * Test that url delegates to the url generator.
	 */
	#[Test]
	public function testUrlDelegatesToUrlGenerator(): void
	{
		/** @var MockObject&UrlGeneratorInterface $urlGenerator */
		$urlGenerator = $this->createMock(UrlGeneratorInterface::class);
		$urlGenerator->expects(self::once())
			->method('route')
			->with('post', ['id' => 5], UrlType::Absolute)
			->willReturn('https://example.com/post/5')
		;

		$view = new View(
			$urlGenerator,
			self::createStub(AssetsPipelineInterface::class),
			self::createStub(TemplateRendererInterface::class)
		);

		self::assertSame('https://example.com/post/5', $view->url('post', ['id' => 5], UrlType::Absolute));
	}

	/**
	 * Test that path delegates to the url generator.
	 */
	#[Test]
	public function testPathDelegatesToUrlGenerator(): void
	{
		/** @var MockObject&UrlGeneratorInterface $urlGenerator */
		$urlGenerator = $this->createMock(UrlGeneratorInterface::class);
		$urlGenerator->expects(self::once())
			->method('path')
			->with('/about', UrlType::Relative)
			->willReturn('/about')
		;

		$view = new View(
			$urlGenerator,
			self::createStub(AssetsPipelineInterface::class),
			self::createStub(TemplateRendererInterface::class)
		);

		self::assertSame('/about', $view->path('/about'));
	}

	/**
	 * Test that asset delegates to the asset pipeline.
	 */
	#[Test]
	public function testAssetDelegatesToAssetPipeline(): void
	{
		/** @var AssetsPipelineInterface&MockObject $assetPipeline */
		$assetPipeline = $this->createMock(AssetsPipelineInterface::class);
		$assetPipeline->expects(self::once())
			->method('url')
			->with('app')
			->willReturn('/assets/app.js')
		;

		$view = new View(
			self::createStub(UrlGeneratorInterface::class),
			$assetPipeline,
			self::createStub(TemplateRendererInterface::class)
		);

		self::assertSame('/assets/app.js', $view->asset('app'));
	}
}
