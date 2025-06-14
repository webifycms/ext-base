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

namespace Webify\Base\Infrastructure;

use Dotenv\Dotenv;
use Throwable;
use Webify\Base\Domain\Exception\FileNotExistException;
use Webify\Base\Domain\Exception\TranslatableRuntimeException;
use Webify\Base\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Dependency\DependencyService;
use Yii;
use yii\base\View as BaseView;
use yii\console\Application as ConsoleApplication;
use yii\helpers\Url;
use yii\web\Application as WebApplication;
use yii\web\View as WebView;

use function function_exists;

if (!function_exists('log_message')) {
	/**
	 * @param string               $type     supported types: info, warning, debug, trace
	 * @param array<string>|string $message  the message to log
	 * @param string               $category the message category defaults to application
	 */
	function log_message(string $type, array|string $message, string $category = 'application'): void
	{
		match ($type) {
			'info'    => Yii::info($message, $category),
			'warning' => Yii::warning($message, $category),
			'debug', 'trace' => Yii::debug($message, $category),
			default => Yii::error($message, $category),
		};
	}
}

if (!function_exists('set_alias')) {
	/**
	 * Sets an alias for a path.
	 *
	 * @see Yii::setAlias()
	 */
	function set_alias(string $alias, string $path): void
	{
		Yii::setAlias($alias, $path);
	}
}

if (!function_exists('get_alias')) {
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
}

if (!function_exists('dependency')) {
	/**
	 * Returns the dependency service instance.
	 */
	function dependency(): DependencyServiceInterface
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
}

if (!function_exists('load_evn_variables')) {
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
}

if (!function_exists('get_env_variable')) {
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
}

if (!function_exists('app')) {
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
}

if (!function_exists('console_app')) {
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
}

if (!function_exists('administration_path')) {
	/**
	 * Returns the administration path.
	 */
	function administration_path(): string
	{
		// @phpstan-ignore-next-line
		return dependency()->getContainer()->get(WebApplicationServiceInterface::class)->getAdministrationPath();
	}
}

if (!function_exists('administration')) {
	/**
	 * Returns the administration service instance.
	 */
	function administration(): AdministrationServiceInterface
	{
		// @phpstan-ignore-next-line
		return dependency()->getContainer()->get(AdministrationServiceInterface::class);
	}
}

if (!function_exists('in_administration')) {
	/**
	 * Check the weather in administration.
	 */
	function in_administration(): bool
	{
		return administration()->inAdministration();
	}
}

if (!function_exists('administration_url')) {
	/**
	 * Returns the administration url based on the given parameters.
	 *
	 * @param null|array<string,string>|string $url
	 */
	function administration_url(null|array|string $url = null, bool|string $scheme = false): string
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
}

if (!function_exists('view')) {
	/**
	 * Returns the framework's view component.
	 */
	function view(): BaseView|WebView
	{
		return app()->getView();
	}
}

if (!function_exists('url')) {
	/**
	 * Creates the url based on the given parameters.
	 *
	 * @param array<string,string>|string $url
	 */
	function url(array|string $url, bool|string $scheme = false): string
	{
		return Url::to($url, $scheme);
	}
}

if (!function_exists('request_url')) {
	/**
	 * Returns the current requested url.
	 */
	function request_url(): string
	{
		return Url::to();
	}
}

if (!function_exists('home_url')) {
	/**
	 * Returns the home url.
	 */
	function home_url(bool|string $scheme = false): string
	{
		return Url::home($scheme);
	}
}

if (!function_exists('remember_url')) {
	/**
	 * Sets the URL to be remembered, and later it can be retrieved by previous_url().
	 *
	 * @param array<string,string>|string $url
	 */
	function remember_url(array|string $url, ?string $name = null): void
	{
		Url::remember($url, $name);
	}
}

if (!function_exists('previous_url')) {
	/**
	 * Returns the URL previously remember or remembered.
	 */
	function previous_url(?string $name = null): ?string
	{
		return Url::previous($name);
	}
}

if (!function_exists('translate')) {
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
}
