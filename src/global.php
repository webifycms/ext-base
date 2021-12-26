<?php

/**
 * The `global` file contains some useful functions that can be used globally. But we encourage them to use only in the
 * controllers, templates and config files.
 */

use OneCMS\Base\Application\Config\Config;
use OneCMS\Base\Application\Config\ConfigInterface;
use OneCMS\Base\Infrastructure\Framework\Console\Application\ConsoleApplication;
use OneCMS\Base\Infrastructure\Framework\Console\Application\ConsoleApplicationInterface;
use OneCMS\Base\Infrastructure\Framework\Dependency\Dependency;
use OneCMS\Base\Infrastructure\Framework\Dependency\DependencyInterface;
use OneCMS\Base\Infrastructure\Framework\Web\Application\WebApplication;
use OneCMS\Base\Infrastructure\Framework\Web\Application\WebApplicationInterface;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Menu;

if (!function_exists('log_message')) {
    /**
     * @param string $type supported types: info, warning, debug, trace
     * @param array|string $message the message to log
     * @param string $category the message category defaults to application
     */
    function log_message(string $type, $message, string $category = 'application')
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
     * @param string $alias
     * @param string $path
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
     * @param string $alias
     *
     * @return string|null
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

if (!function_exists('dependency')) {
    /**
     * @return DependencyInterface
     */
    function dependency(): DependencyInterface
    {
        try {
            return Yii::$container->get(DependencyInterface::class);
        } catch (Throwable $throwable) {
            return new Dependency();
        }
    }
}

if (!function_exists('configure')) {
    /**
     * @param array $configurations
     * @param string $type default is web
     */
    function configure(array $configurations = [], string $type = 'web'): void
    {
        $configObject = new Config($configurations);
        $bootstraps = [];

        dependency()->getContainer()->setSingleton(
            ConfigInterface::class,
            fn () => $configObject
        );

        // handling bootstrap classes
        if (!empty($configurations['bootstrap'])) {
            foreach ($configurations['bootstrap'] as $class) {
                $bootstraps[] = new $class(dependency());
            }
        }

        if ($type === 'web') {
            $app = new WebApplication(dependency(), $configObject);

            dependency()->getContainer()->setSingleton(
                WebApplicationInterface::class,
                fn () => $app
            );
        }

        if ($type === 'console') {
            $app = new ConsoleApplication(dependency(), $configObject);

            dependency()->getContainer()->setSingleton(
                ConsoleApplicationInterface::class,
                fn () => $app
            );
        }

        // call init from all the bootstrap classes
        if ($app) {
            foreach ($bootstraps as $class) {
                $class->init($app);
            }
        }
    }
}

if (!function_exists('config')) {
    /**
     * @return ConfigInterface|object|string
     */
    function config()
    {
        return dependency()->get(ConfigInterface::class);
    }
}

if (!function_exists('app')) {
    /**
     * @return WebApplicationInterface
     */
    function app(): WebApplicationInterface
    {
        return dependency()->get(WebApplicationInterface::class);
    }
}

if (!function_exists('console')) {
    /**
     * @return ConsoleApplicationInterface
     */
    function console(): ConsoleApplicationInterface
    {
        return dependency()->get(ConsoleApplicationInterface::class);
    }
}

if (!function_exists('in_administration')) {
    /**
     * @return bool
     */
    function in_administration(): bool
    {
        return app()->getAdministration()->inAdministration();
    }
}

if (!function_exists('administration_path')) {
    /**
     * @return string
     */
    function administration_path(): string
    {
        return app()->getAdministration()->getPath();
    }
}

if (!function_exists('administration_url')) {
    /**
     * @return string
     */
    function administration_url(): string
    {
        return app()->getAdministration()->getUrl();
    }
}

if (!function_exists('view')) {
    /**
     * @return View
     */
    function view(): View
    {
        return app()->getComponent()->getView();
    }
}

if (!function_exists('url')) {
    /**
     * Creates the url based on the given parameters.
     *
     * @param array|string|null $url
     * @param bool|string $scheme
     * @return string
     */
    function url($url = null, $scheme = false): string
    {
        return Url::to($url, $scheme);
    }
}

if (!function_exists('home_url')) {
    /**
     * Returns the home url.
     *
     * @param bool|string $scheme
     * @return string
     */
    function home_url($scheme = false): string
    {
        return Url::home($scheme);
    }
}

if (!function_exists('remember_url')) {
    /**
     * Returns the URL previously remember or remembered.
     *
     * @param string|array $url
     * @param string|null $name
     */
    function remember_url($url, ?string $name = null): void
    {
        Url::remember($url, $name);
    }
}

if (!function_exists('previous_url')) {
    /**
     * Returns the URL previously remember or remembered.
     *
     * @param string|null $name
     * @return string
     */
    function previous_url(?string $name = null): string
    {
        return Url::previous($name);
    }
}

if (!function_exists('widget')) {
    /**
     * @param string $name
     * @param array $config
     *
     * @return string|null
     */
    function widget(string $name, array $config): ?string
    {
        switch ($name) {
            case 'menu':
                $class = Menu::class;
                break;
            case 'grid':
                $class = GridView::class;
                break;
            default:
                $class = null;
                break;
        }

        if ($class !== null) {
            return $class::widget($config);
        }

        return null;
    }
}
