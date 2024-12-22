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

use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;

use function Webify\Base\Infrastructure\get_alias;
use function Webify\Base\Infrastructure\set_alias;

/**
 * Handles the bootstrapping process of the web application by registering dependencies
 * and initializing required services.
 *
 * This class extends the BaseWebBootstrapService and adheres to the
 * RegisterDependencyBootstrapInterface, ensuring that all necessary dependencies
 * are registered and initialized during the application's lifecycle.
 */
final class WebBootstrapService extends BaseWebBootstrapService implements RegisterDependencyBootstrapInterface
{
	/**
	 * The class constructor.
	 */
	public function __construct(
		DependencyServiceInterface $dependencyService,
		WebApplicationServiceInterface $appService
	) {
		set_alias('@Base', '@Extensions/ext-base');
		parent::__construct($dependencyService, $appService);
	}

	public function dependencies(): array
	{
		return include_once get_alias('@Base/config/dependencies.php');
	}

	public function init(): void {}
}
