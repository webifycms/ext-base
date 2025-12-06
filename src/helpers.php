<?php

/**
 * The file is part of the "webifycms/app", WebifyCMS extension package.
 *
 * @see https://webifycms.com
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

use Dotenv\Dotenv;
use Webify\Base\Domain\Exception\FileNotExistException;
use Webify\Base\Domain\Exception\TranslatableRuntimeException;
use Webify\Base\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Dependency\DependencyService;
use yii\base\View as BaseView;
use yii\console\Application as ConsoleApplication;
use yii\helpers\Url;
use yii\web\Application as WebApplication;
use yii\web\View as WebView;

/**
 * Useful constants.
 */
const ENV_PRODUCTION  = 'prod';
const ENV_DEVELOPMENT = 'dev';

/**
 * @param string               $type     supported types: info, warning, debug, trace
 * @param array<string>|string $message  the message to log
 * @param string               $category the message category defaults to application
 */
function log_message(string $type, array|string $message, string $category = 'application'): void
{
	match ($type) {
		'info' => Yii::info($message, $category),
		'warning' => Yii::warning($message, $category),
		'debug', 'trace' => Yii::debug($message, $category),
		default => Yii::error($message, $category),
	};
}

/**
 * Sets an alias for a path.
 *
 * @see Yii::setAlias()
 */
function set_alias(string $alias, string $path): void
{
	Yii::setAlias($alias, $path);
}

/**
 * Resolves a path from alias.
 *
 * @see Yii::getAlias()
 */
function get_alias(string $alias): ?string
{
	$alias = Yii::getAlias($alias, false);

	return !$alias ? null : $alias;
}

/**
 * Returns the dependency service instance.
 */
function di(): DependencyServiceInterface
{
	try {
		/**
		 * @var DependencyServiceInterface $service
		 */
		$service = Yii::$container->get(DependencyServiceInterface::class);
	} catch (Throwable) {
		$service = new DependencyService();
	}

	return $service;
}

/**
 * Loads the environment variables added in the '.env' file.
 * So those variables can be now accessed from $_ENV or $_SERVER globals.
 *
 * @param string $path     the '.env' file exist a directory path
 * @param string $fileName defaults to '.env'
 */
function load_env_variables(string $path, string $fileName = '.env'): void
{
	$file = $path . '/' . $fileName;

	if (!file_exists($file)) {
		throw new FileNotExistException(FileNotExistException::MESSAGE_KEY, ['file' => $file]);
	}

	Dotenv::createImmutable($path)->load();
}

/**
 * Returns the env variable value for the given name.
 *
 * @param string $name    variable name
 * @param mixed  $default the default value will be assigned if the variable not set in the env
 */
function get_env_variable(string $name, mixed $default = null): mixed
{
	// @todo Should we check first before call it?
	return $_ENV[$name] ?? $default;
}

/**
 * Returns the framework's web application component.
 */
function app(): WebApplication
{
	if (Yii::$app instanceof WebApplication) {
		return Yii::$app;
	}

	throw new TranslatableRuntimeException('web_app_not_initialized');
}

/**
 * Returns the framework's console application component.
 */
function console_app(): ConsoleApplication
{
	if (Yii::$app instanceof ConsoleApplication) {
		return Yii::$app;
	}

	throw new TranslatableRuntimeException('console_app_not_initialized');
}

/**
 * Check weather in development environment.
 */
function is_dev(): bool
{
	return get_env_variable('APP_ENVIRONMENT', ENV_PRODUCTION) === ENV_DEVELOPMENT;
}

/**
 * Check weather is debug enabled.
 */
function is_debug(): bool
{
	return (bool) get_env_variable('APP_DEBUG', false);
}

/**
 * Returns the administration path.
 */
function administration_path(): string
{
	// @phpstan-ignore-next-line
	return di()->getContainer()->get(WebApplicationServiceInterface::class)->getAdministrationPath();
}

/**
 * Returns the administration service instance.
 */
function administration(): AdministrationServiceInterface
{
	// @phpstan-ignore-next-line
	return di()->getContainer()->get(AdministrationServiceInterface::class);
}

/**
 * Check the weather in administration.
 */
function in_administration(): bool
{
	return administration()->inAdministration();
}

/**
 * Returns the administration url based on the given parameters.
 *
 * @param null|array<string,string>|string $url
 */
function administration_url(array|string|null $url = null, bool|string $scheme = false): string
{
	$prefix = administration()->getUrl() . '/';

	if (null === $url) {
		return url(administration()->getUrl());
	}

	if (is_array($url)) {
		foreach ($url as $key => $element) {
			if (0 == $key) {
				$url[$key] = $prefix . ltrim($element, '/');

				break;
			}
		}
	}

	if (is_string($url)) {
		$url = $prefix . ltrim($url, '/');
	}

	return url($url, $scheme);
}

/**
 * Returns the framework's view component.
 */
function view(): BaseView|WebView
{
	return app()->getView();
}

/**
 * Creates the url based on the given parameters.
 *
 * @param array<string,string>|string $url
 */
function url(array|string $url, bool|string $scheme = false): string
{
	return Url::to($url, $scheme);
}

/**
 * Returns the current requested url.
 */
function request_url(): string
{
	return Url::to();
}

/**
 * Returns the home url.
 */
function home_url(bool|string $scheme = false): string
{
	return Url::home($scheme);
}

/**
 * Sets the URL to be remembered, and later it can be retrieved by previous_url().
 *
 * @param array<string,string>|string $url
 */
function remember_url(array|string $url, ?string $name = null): void
{
	Url::remember($url, $name);
}

/**
 * Returns the URL previously remember or remembered.
 */
function previous_url(?string $name = null): ?string
{
	return Url::previous($name);
}

/**
 * Translate given string.
 *
 * @param array<string,string> $params
 *
 * @see Yii::t
 */
function translate(
	string $category,
	string $message,
	array $params = [],
	?string $language = null
): string {
	return Yii::t($category, $message, $params, $language);
}
