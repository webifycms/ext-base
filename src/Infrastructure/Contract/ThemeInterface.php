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

namespace Webify\Base\Infrastructure\Contract;

use Webify\Base\Domain\ValueObject\TemplatePath;

/**
 * The contract for theme.
 *
 * @todo Think about adding a `build(ContainerInterface $container)` method to `ThemeInterface`, so if the class has any dependencies it can be resolved via the container.
 */
interface ThemeInterface
{
	/**
	 * Returns the ID of the theme.
	 */
	public function getId(): string;

	/**
	 * Returns the name of the theme.
	 */
	public function getName(): string;

	/**
	 * Returns the version of the theme.
	 */
	public function getVersion(): string;

	/**
	 * Returns the theme configuration which should be the `theme.json` data.
	 */
	public function getConfig(): ThemeConfigInterface;

	/**
	 * Returns the absolute path to the root directory of the theme package.
	 */
	public function getRootPath(): string;

	/**
	 * Returns the templates path mapped to the extension.
	 */
	public function getTemplatePath(): TemplatePath;

	/**
	 * Returns the templates path mapped to the extension.
	 *
	 * @return array<string, TemplatePath>
	 */
	public function getTemplateMap(): array;

	/**
	 * Returns the service provider instance of the theme, in case no service provider should return `null`.
	 */
	public function getProvider(): ?ServiceProviderInterface;

	/**
	 * Returns the theme's asset provider if extension built its own assets for publishing.
	 *
	 * @return null|AssetsProviderInterface if not assets to publish, you can return null
	 */
	public function getAssetsProvider(): ?AssetsProviderInterface;
}
