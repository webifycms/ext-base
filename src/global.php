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

if (!function_exists('dependency')) {
    function dependency(): DependencyInterface
    {
        try {
            return Yii::$container->get(DependencyInterface::class);
        } catch (Throwable) {
            return new Dependency();
        }
    }
}

if (!function_exists('configure')) {
    /**
     * @param string $type default is web
     */
    function configure(array $configurations = [], string $type = 'web'): void
    {
        $app = null;
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
     * @return ConfigInterface|string
     */
    function config(): ConfigInterface|string
    {
        return dependency()->get(ConfigInterface::class);
    }
}

if (!function_exists('app')) {
    function app(): WebApplicationInterface
    {
        return dependency()->get(WebApplicationInterface::class);
    }
}

if (!function_exists('console')) {
    function console(): ConsoleApplicationInterface
    {
        return dependency()->get(ConsoleApplicationInterface::class);
    }
}

if (!function_exists('in_administration')) {
    function in_administration(): bool
    {
        return app()->getAdministration()->inAdministration();
    }
}

if (!function_exists('administration_path')) {
    function administration_path(): string
    {
        return app()->getAdministration()->getPath();
    }
}

if (!function_exists('administration_url')) {
    function administration_url(): string
    {
        return app()->getAdministration()->getUrl();
    }
}

if (!function_exists('view')) {
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
     * Returns the URL previously remember or remembered.
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

if (!function_exists('widget')) {
    function widget(string $name, array $config): ?string
    {
        $class = match ($name) {
            'menu' => Menu::class,
            'grid' => GridView::class,
            default => null,
        };

        if ($class !== null) {
            return $class::widget($config);
        }

        return null;
    }
}
