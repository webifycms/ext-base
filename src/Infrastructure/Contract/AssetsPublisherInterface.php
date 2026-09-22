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
 * The asset publisher contract.
 *
 * Publishes asset bundles declared by an {@see AssetsProviderInterface} (an
 * extension or theme that ships frontend assets) from their source directory
 * into the public webroot under `public/assets/<hash>/`.
 */
interface AssetsPublisherInterface
{
	/**
	 * Publishes the assets of the given provider.
	 *
	 * Copies the provider's assets directory contents into
	 * `public/assets/<providerHash>/`. Publishing is a no-op when the target
	 * directory already holds a full mirror of the current source contents
	 * (verified by a completion marker); any missing, partial or stale target is
	 * reconciled with the source before the request continues.
	 */
	public function publish(AssetsProviderInterface $provider): void;
}
