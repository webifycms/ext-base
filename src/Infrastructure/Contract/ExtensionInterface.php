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

namespace Webify\Base\Infrastructure\Contract;

/**
 * The contract that holds the extension package information.
 */
interface ExtensionInterface
{
	/**
	 * Returns the unique identifier for this extension.
	 */
	public function getId(): string;

	/**
	 * Returns the name of the extension.
	 */
	public function getName(): string;

	/**
	 * Returns the list of service provider class names for this extension.
	 *
	 * @return array<class-string<BootstrapServiceProviderInterface|ServiceProviderInterface>>
	 */
	public function getProviders(): array;

	/**
	 * Returns the version of the extension.
	 */
	public function getVersion(): string;
}
