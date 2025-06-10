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

namespace Webify\Base\Domain;

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;

/**
 * Interface for defining the core contract for an extension.
 *
 * This interface outlines the necessary methods that any extension
 * implementation must provide to support identification, versioning,
 * and resource management for the extension.
 */
interface ExtensionInterface
{
	/**
	 * Initialize the extension service.
	 */
	public function initialize(ApplicationServiceInterface $appService): void;

	/**
	 * Retrieves the unique identifier of the extension.
	 */
	public function getId(): string;

	/**
	 * The interface name of the extension.?
	 */
	public function getInterface(): string;

	/**
	 * Retrieves the version of the extension.
	 */
	public function getVersion(): string;

	/**
	 * Retrieves the path of the extension's templates directory.
	 */
	public function getTemplatesPath(): ?string;

	/**
	 * Retrieves the path of the extension's assets directory.
	 */
	public function getAssetsPath(): ?string;

	/**
	 * Retrieves the URL of the extension's assets directory after publishing.
	 */
	public function getAssetsPublishedUrl(): ?string;

	/**
	 * Retrieves the list of the classes that needs to register.
	 *
	 * @return array<string>|array{}
	 */
	public function getRegisterServices(): array;

	/**
	 * Returns the instance of this object.
	 *
	 * @note It should return from the DI container, not a new instance.
	 */
	public static function getInstance(): self;
}
