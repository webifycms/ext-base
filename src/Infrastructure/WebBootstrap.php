<?php

/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure;

use OneCMS\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use OneCMS\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;

/**
 * WebBootstrap bootstrap file for the extension.
 */
class WebBootstrap extends WebBootstrapService implements RegisterDependencyBootstrapInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function dependencies(): array
	{
		return [
		];
	}
}
