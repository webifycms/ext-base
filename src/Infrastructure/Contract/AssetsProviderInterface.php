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

/**
 * The asset provider contract.
 *
 * Implemented (optionally) by an extension or theme to declare the frontend
 * assets it ships. When implemented, the asset publisher publishes the assets
 * into the public webroot under `public/assets/<hash>/`, where `<hash>` is
 * derived from the provider's {@see getHash()}.
 */
interface AssetsProviderInterface
{
	/**
	 * Returns the hash that determines the published directory name.
	 *
	 * The hash must be deterministic for a given provider identity and version,
	 * so that republishing the same version is a no-op.
	 */
	public function getHash(): string;

	/**
	 * Returns the path to the directory containing the built assets.
	 * The path should have given from the application root,
	 * use `ConfigInterface::$themesPath` to construct the directory.
	 *
	 * The directory contents are copied into `public/assets/<hash>/` on publish.
	 */
	public function getDirectory(): string;
}
