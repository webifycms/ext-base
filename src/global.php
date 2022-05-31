<?php

/**
 * The `global` file contains some useful functions that can be used globally. These functions are highly dependeds on 
 * infrastructure layer and we encourage to use only in the controllers, templates and config files or in the infrastructure later.
 */

use Dotenv\Dotenv;
use OneCMS\Base\Domain\Exception\FileNotExistException;
use OneCMS\Base\Domain\Service\Administration\AdministrationServiceInterface;
use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\ConsoleApplicationService;
use OneCMS\Base\Infrastructure\Service\Application\ConsoleApplicationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationService;
use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use OneCMS\Base\Infrastructure\Service\Dependency\DependencyService;
use yii\helpers\Url;
use yii\web\Application;
use yii\console\Application as ConsoleApplication;
use yii\web\View;

if (!function_exists('log_message')) {
    /**
     * @param string $type supported types: info, warning, debug, trace
     * @param array|string $message the message to log
     * @param string $category the message category defaults to application
     */
    function log_message(string $type, array|string $message, string $category = 'application')
    {
        switch ($type) {
            case 'info':
                Yii::info($message, $category);
                break;
            case 'warning':
                Yii::warning($message, $category);
                break;
            case 'debug' | 'trace':
                Yii::debug($message, $category);
                break;
            default:
                Yii::error($message, $category);
                break;
        }
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

if (!function_exists('enable_dev_env')) {
    /**
     * Enables the development environment.
     */
    function enable_dev_env(): void
    {
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_ENV') or define('YII_ENV', 'dev');
    }
}

if (!function_exists(('in_debug'))) {
    /**
     * Check in debug.
     *
     * @return boolean
     */
    function in_debug(): bool
    {
        return YII_DEBUG;
    }
}

if (!function_exists('dependency')) {
    /**
     * Returns the dependency service instance.
     *
     * @return DependencyServiceInterface
     */
    function dependency(): DependencyServiceInterface
    {
        try {
            return Yii::$container->get(DependencyServiceInterface::class);
        } catch (Throwable) {
            return new DependencyService();
        }
    }
}

if (!function_exists('load_evn_variables')) {
    /**
     * Loads the environment variables that were added in the '.env' file. 
     * So those variables can be now access from $_ENV or $_SERVER globals.
     * 
     * @param string $path the '.env' file exist directory
     * @param string $fileName defaults to '.env'
     */
    function load_env_variables(string $path, string $fileName = '.env'): void
    {
        $file = $path . '/' . $fileName;

        if (!file_exists($file)) {
            throw new FileNotExistException('file_not_exist', ['file' => $file]);
        }

        (Dotenv::createImmutable($path))->load();
    }
}

if (!function_exists('get_env_variable')) {
    /**
     * Returns the env variable value for the given name.
     *
     * @param string $name variable name
     * @return mixed
     */
    function get_env_variable(string $name): mixed
    {
        // @todo Should we check first before call it?
        return $_ENV[$name];
    }
}

if (!function_exists('configure')) {
    /**
     * Configure the application.
     * 
     * @param string $type default is "web"
     */
    function configure(array $configurations = [], string $type = 'web'): void
    {
        $app = null;

        // creating web application and register in the container
        if ($type === 'web') {
            $app = new WebApplicationService(dependency(), $configurations);

            dependency()->getContainer()->setSingleton(
                WebApplicationServiceInterface::class,
                fn () => $app
            );
        }

        // creating console application and register in the container
        if ($type === 'console') {
            $app = new ConsoleApplicationService(dependency(), $configurations);

            dependency()->getContainer()->setSingleton(
                ConsoleApplicationServiceInterface::class,
                fn () => $app
            );
        }

        // inititate the bootstrap classes
        if ($app && !empty($configurations['bootstrap'])) {
            foreach ($configurations['bootstrap'] as $class) {
                (new $class(dependency(), $app))->init();
            }
        }
    }
}

if (!function_exists('app')) {
    /**
     * Returns the web application service intance.
     *
     * @return Application
     */
    function app(): Application
    {
        return dependency()->getContainer()->get(WebApplicationServiceInterface::class)->getApplication();
    }
}

if (!function_exists('console')) {
    /**
     * Returns the console application service intance.
     *
     * @return ConsoleApplication
     */
    function console(): ConsoleApplication
    {
        return dependency()->getContainer()->get(ConsoleApplicationServiceInterface::class)->getApplication();
    }
}

if (!function_exists('administration')) {
    /**
     * Returns the administration service instance.
     *
     * @return AdministrationServiceInterface
     */
    function administration(): AdministrationServiceInterface
    {
        return dependency()->getContainer()->get(AdministrationServiceInterface::class);
    }
}

if (!function_exists('in_administration')) {
    /**
     * Undocumented function
     *
     * @return boolean
     */
    function in_administration(): bool
    {
        return administration()->inAdministration();
    }
}

if (!function_exists('administration_path')) {
    function administration_path(): string
    {
        return administration()->getPath();
    }
}

if (!function_exists('administration_url')) {
    function administration_url(): string
    {
        return administration()->getUrl();
    }
}

if (!function_exists('view')) {
    function view(): View
    {
        return app()->getView();
    }
}

if (!function_exists('url')) {
    /**
     * Creates the url based on the given parameters.
     *
     * @param array|string|null $url
     */
    function url($url = null, bool|string $scheme = false): string
    {
        return Url::to($url, $scheme);
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
     * Sets the URL to be remember and later it can be retrieve by previous_url().
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
    function previous_url(?string $name = null): string
    {
        return Url::previous($name);
    }
}

if (!function_exists('translate')) {
    /**
     * Translate given string.
     * @see \Yii::t()
     *
     * @param string $category
     * @param string $message
     * @param array $params
     * @param string|null $language
     * @return void
     */
    function translate(
        string $category,
        string $message,
        array $params = [],
        ?string $language = null
    ) {
        return Yii::t($category, $message, $params, $language);
    }
}
