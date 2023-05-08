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
use Webify\Base\Domain\Exception\FileNotExistException;
use Webify\Base\Domain\Service\Administration\AdministrationServiceInterface;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Webify\Base\Infrastructure\Service\Application\ConsoleApplicationService;
use Webify\Base\Infrastructure\Service\Application\WebApplicationService;
use Webify\Base\Infrastructure\Service\Dependency\DependencyService;
use yii\base\Application;
use yii\base\View;
use yii\console\Application as ConsoleApplication;
use yii\helpers\Url;
use yii\web\Application as WebApplication;
use yii\web\View as WebView;

if (!\function_exists('log_message')) {
	/**
	 * @param string               $type     supported types: info, warning, debug, trace
	 * @param array<string>|string $message  the message to log
	 * @param string               $category the message category defaults to application
	 */
	function log_message(string $type, array|string $message, string $category = 'application'): void
	{
		match ($type) {
			'info'    => \Yii::info($message, $category),
			'warning' => \Yii::warning($message, $category),
			'debug', 'trace' => \Yii::debug($message, $category),
			default => \Yii::error($message, $category),
		};
	}
}

if (!\function_exists('set_alias')) {
	/**
	 * Sets an alias for a path.
	 *
	 * @see Yii::setAlias()
	 */
	function set_alias(string $alias, string $path): void
	{
		\Yii::setAlias($alias, $path);
	}
}

if (!\function_exists('get_alias')) {
	/**
	 * Resolves a path from alias.
	 *
	 * @see Yii::getAlias()
	 */
	function get_alias(string $alias): ?string
	{
		$alias = \Yii::getAlias($alias, false);

		return !$alias ? null : $alias;
	}
}

if (!\function_exists('enable_dev_env')) {
	/**
	 * Enables the development environment.
	 */
	function enable_dev_env(): void
	{
		\defined('YII_DEBUG') || \define('YII_DEBUG', true);
		\defined('YII_ENV') || \define('YII_ENV', 'dev');
	}
}

if (!\function_exists('in_debug')) {
	/**
	 * Check in debug.
	 */
	function in_debug(): bool
	{
		return YII_DEBUG;
	}
}

if (!\function_exists('dependency')) {
	/**
	 * Returns the dependency service instance.
	 */
	function dependency(): DependencyServiceInterface
	{
		try {
			return \Yii::$container->get(DependencyServiceInterface::class);
		} catch (\Throwable) {
			return new DependencyService();
		}
	}
}

if (!\function_exists('load_evn_variables')) {
	/**
	 * Loads the environment variables that were added in the '.env' file.
	 * So those variables can be now access from $_ENV or $_SERVER globals.
	 *
	 * @param string $path     the '.env' file exist directory
	 * @param string $fileName defaults to '.env'
	 */
	function load_env_variables(string $path, string $fileName = '.env'): void
	{
		$file = $path . '/' . $fileName;

		if (!file_exists($file)) {
			throw new FileNotExistException('file_not_exist', ['file' => $file]);
		}

		Dotenv::createImmutable($path)->load();
	}
}

if (!\function_exists('get_env_variable')) {
	/**
	 * Returns the env variable value for the given name.
	 *
	 * @param string $name variable name
	 */
	function get_env_variable(string $name): mixed
	{
		// @todo Should we check first before call it?
		return $_ENV[$name];
	}
}

if (!\function_exists('configure')) {
	/**
	 * Configure the application.
	 *
	 * @param array<string, mixed> $configurations
	 * @param null|string          $type           allowed value is only 'console' because default is always 'web' app
	 */
	function configure(array $configurations = [], ?string $type = null): void
	{
		// creating web application and register in the container
		$app = new WebApplicationService(dependency(), $configurations);

		// creating console application and register in the container
		if ('console' === $type) {
			$app = new ConsoleApplicationService(dependency(), $configurations);
		}

		dependency()->getContainer()->setSingleton(
			ApplicationServiceInterface::class,
			fn () => $app
		);

		// initiate the bootstrap classes
		if (!empty($configurations['bootstrap'])) {
			foreach ($configurations['bootstrap'] as $class) {
				(new $class(dependency(), $app))->init();
			}
		}
	}
}

if (!\function_exists('app')) {
	/**
	 * Returns the framework's application component.
	 */
	function app(): Application|ConsoleApplication|WebApplication
	{
		return dependency()->getContainer()->get(ApplicationServiceInterface::class)->getApplication();
	}
}

if (!\function_exists('administration')) {
	/**
	 * Returns the administration service instance.
	 */
	function administration(): AdministrationServiceInterface
	{
		return dependency()->getContainer()->get(AdministrationServiceInterface::class);
	}
}

if (!\function_exists('in_administration')) {
	/**
	 * Check weather in administration.
	 */
	function in_administration(): bool
	{
		return administration()->inAdministration();
	}
}

if (!\function_exists('administration_path')) {
	/**
	 * Returns the administration path.
	 */
	function administration_path(): string
	{
		return administration()->getPath();
	}
}

if (!\function_exists('administration_url')) {
	/**
	 * Returns the administration url.
	 */
	function administration_url(): string
	{
		return administration()->getUrl();
	}
}

if (!\function_exists('view')) {
	/**
	 * Returns the framework's view component.
	 */
	function view(): View|WebView
	{
		return app()->getView();
	}
}

if (!\function_exists('url')) {
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

if (!\function_exists('request_url')) {
	/**
	 * Returns the current requested url.
	 */
	function request_url(): string
	{
		return Url::to();
	}
}

if (!\function_exists('home_url')) {
	/**
	 * Returns the home url.
	 */
	function home_url(bool|string $scheme = false): string
	{
		return Url::home($scheme);
	}
}

if (!\function_exists('remember_url')) {
	/**
	 * Sets the URL to be remember and later it can be retrieve by previous_url().
	 *
	 * @param array<string,string>|string $url
	 */
	function remember_url(array|string $url, ?string $name = null): void
	{
		Url::remember($url, $name);
	}
}

if (!\function_exists('previous_url')) {
	/**
	 * Returns the URL previously remember or remembered.
	 */
	function previous_url(?string $name = null): string
	{
		return Url::previous($name);
	}
}

if (!\function_exists('translate')) {
	/**
	 * Translate given string.
	 *
	 * @param array<string,string> $params
	 *
	 * @see \Yii::t()
	 */
	function translate(
		string $category,
		string $message,
		array $params = [],
		?string $language = null
	): string {
		return \Yii::t($category, $message, $params, $language);
	}
}
