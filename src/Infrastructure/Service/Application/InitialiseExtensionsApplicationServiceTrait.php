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

use Webify\Base\Domain\ExtensionInterface;

/**
 * Trait to help extension initialisation.
 */
trait InitialiseExtensionsApplicationServiceTrait
{
	use InitialiseRegisterServicesApplicationServiceTrait;

	/**
	 * Initialise the given extensions.
	 *
	 * @param array<string> $extensions
	 */
	protected function initialiseExtensions(array $extensions): void
	{
		foreach ($extensions as $class) {
			/**
			 * @var ExtensionInterface $extension
			 */
			$extension                                    = new $class();
			$this->extensions[$extension->getInterface()] = $extension;

			$this->initialisePreRegisterServices($extension->getRegisterServices());
		}
	}

	/**
	 * After the application has been initialised.
	 *
	 * @param ExtensionInterface[] $extensions
	 */
	protected function postExtensionsInitialisation(array $extensions): void
	{
		foreach ($extensions as $extension) {
			$extension->initialize($this);
			$this->initialisePostRegisterServices($extension->getRegisterServices());
		}
	}
}
