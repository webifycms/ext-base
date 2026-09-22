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

namespace Webify\Base\Test\Unit\Infrastructure\Theme\Example\Active;

use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Contract\{
	AssetsProviderInterface,
	ServiceProviderInterface,
	ThemeConfigInterface,
	ThemeInterface,
};
use Webify\Base\Infrastructure\Service\ThemeConfig;

/**
 * Example active theme fixture for testing purposes.
 *
 * @internal
 */
final readonly class ActiveTheme implements ThemeInterface
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private ThemeConfigInterface $config = new ThemeConfig([
			'id'      => 'example-active',
			'name'    => 'Example Active Theme',
			'version' => '2.0.0',
		]),
		// The theme class resolves its own root path, anchored at its own
		// location — mirroring how a real PHP theme (e.g. Canvas) resolves it.
		private string $rootPath = __DIR__
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function getId(): string
	{
		return (string) $this->config->get('id');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return (string) $this->config->get('name', $this->getId());
	}

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): string
	{
		return (string) $this->config->get('version', '0.0.0');
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfig(): ThemeConfigInterface
	{
		return $this->config;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRootPath(): string
	{
		return $this->rootPath;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTemplatePath(): TemplatePath
	{
		return TemplatePath::fromString($this->rootPath . '/templates');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @return array<string, TemplatePath>
	 */
	public function getTemplateMap(): array
	{
		return ['blog' => TemplatePath::fromString($this->rootPath . '/blog')];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getProvider(): ServiceProviderInterface
	{
		return new class implements ServiceProviderInterface {
			/**
			 * {@inheritDoc}
			 */
			public function getDefinitions(): array
			{
				return ['active.definition' => 'active-value'];
			}
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAssetsProvider(): ?AssetsProviderInterface
	{
		return null;
	}
}
