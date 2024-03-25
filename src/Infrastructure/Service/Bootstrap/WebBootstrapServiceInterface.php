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

namespace Webify\Base\Infrastructure\Service\Bootstrap;

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface as DomainApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\web\Application;

/**
 * WebBootstrapServiceInterface.
 */
interface WebBootstrapServiceInterface
{
	/**
	 * Returns the web application service instance.
	 */
	public function getApplicationService(): ApplicationServiceInterface|DomainApplicationServiceInterface|WebApplicationServiceInterface;

	/**
	 * Returns the web application instance.
	 */
	public function getApplication(): Application;
}
