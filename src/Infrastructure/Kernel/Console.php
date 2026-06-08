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

namespace Webify\Base\Infrastructure\Kernel;

use Symfony\Component\Console\Application;

/**
 * Console kernel handles the console command lifecycle.
 */
final readonly class Console
{
	/**
	 * The constructor.
	 *
	 * @param Application $console the console application instance
	 */
	public function __construct(
		private Application $console
	) {}

	/**
	 * Handles the console command lifecycle.
	 */
	public function handle(): int
	{
		return $this->console->run();
	}
}
