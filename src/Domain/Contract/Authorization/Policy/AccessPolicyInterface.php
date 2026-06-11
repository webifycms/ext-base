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

namespace Webify\Base\Domain\Contract\Authorization\Policy;

use Webify\Base\Domain\Contract\Authorization\{AuthorizableResourceInterface, AuthorizableSubjectInterface};

/**
 * AccessPolicyInterface defines the contract for authorization policy.
 *
 * Checks whether the given subject is authorized to access the specified resource.
 */
interface AccessPolicyInterface
{
	/**
	 * Checks whether the given subject is authorized to access the specified resource.
	 *
	 * @param string                        $action   the action being checked for authorization
	 * @param AuthorizableSubjectInterface  $subject  the subject whose authorization is being evaluated
	 * @param AuthorizableResourceInterface $resource the resource for which access is being checked
	 *
	 * @return bool returns true if the subject is authorized to access the resource, false otherwise
	 */
	public function isAllowed(
		string $action,
		AuthorizableSubjectInterface $subject,
		AuthorizableResourceInterface $resource
	): bool;
}
