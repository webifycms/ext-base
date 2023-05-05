<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @license Copyright (c) 2022 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace Webify\Base\Infrastructure;

use Webify\Base\Domain\Service\Validator\EmailValidatorServiceInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\RegisterDependencyBootstrapInterface;
use Webify\Base\Infrastructure\Service\Bootstrap\WebBootstrapService;
use Webify\Base\Infrastructure\Service\Validator\EmailValidatorService;
use yii\validators\EmailValidator;

/**
 * WebBootstrap bootstrap file for the extension.
 */
class WebBootstrap extends WebBootstrapService implements RegisterDependencyBootstrapInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function dependencies(): array
	{
		return [
			EmailValidatorServiceInterface::class => fn () => new EmailValidatorService(new EmailValidator()),
		];
	}
}
