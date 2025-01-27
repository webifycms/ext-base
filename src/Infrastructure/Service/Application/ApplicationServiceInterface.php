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

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface as DomainApplicationServiceInterface;

/**
 * Interface ApplicationServiceInterface.
 *
 * Defines the contract for managing application services and properties.
 */
interface ApplicationServiceInterface extends DomainApplicationServiceInterface
{
	/**
	 * Returns the service instance for the given name if registered in the container.
	 *
	 * @param string               $name   alias or fully qualified class name that has registered with the container
	 * @param array<string, mixed> $params array of constructor parameters values @see \yii\di\Container::get
	 * @param array<string, mixed> $config array of name-value pairs that will be used to initialize the object properties
	 */
	public function getService(string $name, array $params, array $config): mixed;

	/**
	 * Shortcut and safe method to get the value of the framework application property.
	 */
	public function getApplicationProperty(string $name): mixed;

	/**
	 * Shortcut and safe method to set the value of the framework application property.
	 */
	public function setApplicationProperty(string $name, mixed $value): void;
}
