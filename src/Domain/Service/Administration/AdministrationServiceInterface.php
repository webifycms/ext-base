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

namespace Webify\Base\Domain\Service\Administration;

/**
 * Interface for defining administration services.
 */
interface AdministrationServiceInterface
{
	/**
	 * Returns the administration path.
	 */
	public function getPath(): string;

	/**
	 * Returns absolute url of the administration.
	 */
	public function getUrl(): string;

	/**
	 * Returns true if in administration, otherwise returns false.
	 */
	public function inAdministration(): bool;
}
