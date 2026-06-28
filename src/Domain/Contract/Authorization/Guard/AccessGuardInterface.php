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

namespace Webify\Base\Domain\Contract\Authorization\Guard;

use Webify\Base\Domain\Contract\Authorization\{AuthorizableResourceInterface, AuthorizableSubjectInterface};
use Webify\Base\Domain\Exception\AccessDeniedException;

/**
 * AccessGuardInterface defines the contract for authorization guard.
 *
 * Enforces access checks for a given action on a resource by a subject and if violates throws an exception.
 */
interface AccessGuardInterface
{
	/**
	 * Checks if the given subject has permission to perform the specified action on the resource
	 * and throws an `AccessDeniedException` if access is denied.
	 *
	 * @param string                        $action   the action being performed
	 * @param AuthorizableSubjectInterface  $subject  the subject performing the action
	 * @param AuthorizableResourceInterface $resource the resource being accessed
	 *
	 * @throws AccessDeniedException if access is denied
	 */
	public function guard(
		string $action,
		AuthorizableSubjectInterface $subject,
		AuthorizableResourceInterface $resource
	): void;
}
