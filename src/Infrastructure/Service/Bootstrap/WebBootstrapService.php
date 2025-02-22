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

use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Domain\Service\Config\ConfigServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;

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
final class WebBootstrapService extends BaseWebBootstrapService implements RegisterDependenciesBootstrapInterface
{
	/**
	 * The class constructor.
	 */
	public function __construct(
		DependencyServiceInterface $dependencyService,
		ConfigServiceInterface $configService,
	) {
		set_alias('@Base', '@Extensions/ext-base');

		parent::__construct($dependencyService, $configService);
	}

	public function dependencies(): array
	{
		return require get_alias('@Base/config/dependencies.php');
	}

	public function bootstrap(ApplicationServiceInterface $appService): void {}
}
