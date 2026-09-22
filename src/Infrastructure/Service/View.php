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

namespace Webify\Base\Infrastructure\Service;

use Webify\Base\Infrastructure\Contract\{
	AssetsPipelineInterface,
	ExtensionInterface,
	TemplateRendererInterface,
	UrlGeneratorInterface,
	UrlType,
	ViewInterface,
};

/**
 * View service.
 */
final class View implements ViewInterface
{
	/**
	 * {@inheritdoc}
	 */
	public string $layout {
		get => $this->layout;
		set => $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public ?ExtensionInterface $extension = null {
		get => $this->extension;
		set => $value;
	}

	/**
	 * The constructor.
	 */
	public function __construct(
		private readonly UrlGeneratorInterface $urlGenerator,
		private readonly AssetsPipelineInterface $assetPipeline,
		private readonly TemplateRendererInterface $renderer
	) {}

	/**
	 * {@inheritdoc}
	 */
	public function render(string $template, array $data = [], ?string $layout = null): string
	{
		if (null !== $layout) {
			$this->layout = $layout;
		}

		$data['view'] = $this;

		return $this->renderer->render($template, $data, $this->extension);
	}

	/**
	 * {@inheritdoc}
	 */
	public function url(string $name, array $parameters = [], UrlType $type = UrlType::Relative): string
	{
		return $this->urlGenerator->route($name, $parameters, $type);
	}

	/**
	 * {@inheritdoc}
	 */
	public function asset(string $file): string
	{
		return $this->assetPipeline->url($file);
	}

	/**
	 * {@inheritDoc}
	 */
	public function assetEntrypoint(string $entry): string
	{
		return $this->assetPipeline->entrypoint($entry);
	}

	/**
	 * {@inheritdoc}
	 */
	public function path(string $path, UrlType $type = UrlType::Relative): string
	{
		return $this->urlGenerator->path($path, $type);
	}
}
