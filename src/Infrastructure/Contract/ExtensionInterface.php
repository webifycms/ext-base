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
 * The contract that holds the extension information.
 */
interface ExtensionInterface
{
	/**
	 * Returns the unique identifier for this extension.
	 */
	public function getId(): string;

	/**
	 * Returns the slug for this extension.
	 *
	 * Note: Don't rely on slug for uniqueness, instead use the `getId()`
	 */
	public function getSlug(): string;

	/**
	 * Returns the name of the extension.
	 */
	public function getName(): string;

	/**
	 * Returns the list of service provider class names for this extension.
	 *
	 * @return list<class-string<BootstrapServiceProviderInterface|ServiceProviderInterface>>
	 */
	public function getProviders(): array;

	/**
	 * Returns the version of the extension.
	 */
	public function getVersion(): string;

	/**
	 * Returns the templates path mapped to the extension.
	 */
	public function getTemplatePath(): TemplatePath;

	/**
	 * Returns the extension's asset provider if extension built its own assets for publishing.
	 *
	 * @return null|AssetsProviderInterface if not assets to publish, you can return null
	 */
	public function getAssetsProvider(): ?AssetsProviderInterface;
}
