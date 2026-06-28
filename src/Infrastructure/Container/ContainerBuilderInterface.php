<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Container;

use Psr\Container\ContainerInterface;
use Webify\Base\Infrastructure\Service\Application;

/**
 * Interface for building a PSR container.
 */
interface ContainerBuilderInterface
{
	/**
	 * Build the PSR container.
	 */
	public function build(Application $app): ContainerInterface;
}
