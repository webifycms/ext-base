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

namespace Webify\Base\Infrastructure\Service\Application;

use Webify\Base\Infrastructure\Service\Register\PostRegisterServiceInterface;
use Webify\Base\Infrastructure\Service\Register\PreRegisterServiceInterface;

/**
 * Trait to handle the initialisation of the pre-register and post-register services.
 */
trait InitialiseRegisterServicesApplicationServiceTrait
{
	/**
	 * Initialise pre-register services.
	 *
	 * @param array<string> $services an array of service class names to be processed
	 */
	protected function initialisePreRegisterServices(array $services): void
	{
		foreach ($services as $class) {
			$interfaces = class_implements($class);

			if (false === $interfaces) {
				continue;
			}

			if (in_array(PreRegisterServiceInterface::class, $interfaces)) {
				/**
				 * @var PreRegisterServiceInterface $service
				 */
				$service = new $class();

				$service->register($this->container);
			}
		}
	}

	/**
	 * Initialise post-register services.
	 *
	 * @param array<string> $services an array of service class names to be processed
	 */
	protected function initialisePostRegisterServices(array $services): void
	{
		foreach ($services as $class) {
			$interfaces = class_implements($class);

			if (false === $interfaces) {
				continue;
			}

			if (in_array(PostRegisterServiceInterface::class, $interfaces)) {
				/**
				 * @var PostRegisterServiceInterface $service
				 */
				$service = new $class();

				$service->register($this);
			}
		}
	}
}
