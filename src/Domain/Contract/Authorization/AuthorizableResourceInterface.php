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

namespace Webify\Base\Domain\Contract\Authorization;

/**
 * The contract for authorizable resources.
 */
interface AuthorizableResourceInterface
{
	/**
	 * Retrieves the scope as a string. The scope defines the level of access to the resource.
	 */
	public function resourceScope(): string;

	/**
	 * Retrieves the type as a string. The type defines the category of the resource.
	 */
	public function resourceType(): string;

	/**
	 * Retrieves the identifier as a string.
	 */
	public function resourceId(): string;

	/**
	 * Retrieves the owner ID associated with the resource.
	 *
	 * @return null|string the owner ID of the resource, or null if not available
	 */
	public function ownerId(): ?string;

	/**
	 * Retrieves the tenant ID, if available. The tenant ID is used to identify the tenant associated with the resource.
	 *
	 * @return null|string the tenant ID or null if not set
	 */
	public function tenantId(): ?string;
}
