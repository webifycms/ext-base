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

use function Webify\Base\Infrastructure\get_alias;

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
     * @inheritDoc
     */
	public function dependencies(): array
	{
		return include_once get_alias('@Base/config/dependencies.php');
	}

    /**
     * @inheritDoc
     */
	public function init(): void {}
}
