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

use Webify\Base\Domain\Service\Validator\EmailValidatorServiceInterface;
use Webify\Base\Infrastructure\Service\Administration\PrimaryMenuAdministrationService;
use Webify\Base\Infrastructure\Service\Administration\PrimaryMenuAdministrationServiceInterface;
use Webify\Base\Infrastructure\Service\Validator\EmailValidatorService;
use yii\validators\EmailValidator;

use function Webify\Base\Infrastructure\app;

return [
	'definitions' => [
		EmailValidatorServiceInterface::class            => fn () => new EmailValidatorService(new EmailValidator()),
		PrimaryMenuAdministrationServiceInterface::class => fn () => new PrimaryMenuAdministrationService(
			app()->getView() // @phpstan-ignore argument.type
		),
	],
	'singletons' => [],
];
