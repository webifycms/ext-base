<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2022 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author  Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace Webify\Base\Domain\Service\Identity;

/**
 * Interface IdentityServiceInterface.
 */
interface IdentityServiceInterface
{
	/**
	 * @return mixed
	 */
	public function getId(): mixed;
}
