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
use Webify\Base\Infrastructure\Service\Validator\EmailValidatorService;
use yii\di\Container;
use yii\validators\EmailValidator;

use function Webify\Base\Infrastructure\dependency;

/**
 * @var Container $container
 */
$container = dependency()->getContainer();

return [
	'definitions' => [
		EmailValidatorServiceInterface::class => fn () => new EmailValidatorService(new EmailValidator()),
	],
	'singletons' => [],
];
