# ext-base

The WebifyCMS base extension serves as the foundation for the entire application,
holding abstractions and reusable components. It is adjusted as needed when new features are introduced.

---

- [Architecture](#architecture)
  - [Dependency Rules](#dependency-rules)
  - [Adding Classes to a Layer](#adding-classes-to-a-layer)
  - [Architecture Tests](#architecture-tests)
- [Directory Structure](#directory-structure)
- [Design Patterns](#design-patterns)
  - [Domain Layer](#domain-layer)
  - [Application Layer](#application-layer)
  - [Infrastructure Layer](#infrastructure-layer)
  - [Contract Layer](#contract-layer)
- [Infrastructure](#infrastructure)
  - [Environment Variables](#environment-variables)
  - [Configuration](#configuration)
  - [Environment](#environment)
  - [Application Lifecycle](#application-lifecycle)
  - [Container Building](#container-building)
  - [Service Providers](#service-providers)
  - [Extensions](#extensions)
  - [Kernels](#kernels)
  - [Controllers](#controllers)
  - [Error Handling Middleware](#error-handling-middleware)
  - [Complete Bootstrap Example](#complete-bootstrap-example)
  - [Development Server](#development-server)

---

## Architecture

The extension follows a **Domain-Driven Design (DDD)** layered architecture with
a **Clean Architecture** dependency rule set. The source code is organised into
four top-level namespaces under `Webify\Base`:

| Layer | Namespace | Purpose |
|-------|-----------|---------|
| **Domain** | `Webify\Base\Domain` | Enterprise business rules — entities, value objects, domain events, domain services, and domain contracts (interfaces defined by the domain). |
| **Application** | `Webify\Base\Application` | Application-level contracts such as `ConfigInterface`. Thin glue; no infrastructure references. |
| **Infrastructure** | `Webify\Base\Infrastructure` | Framework and library implementations — container, kernels, HTTP middleware, database repositories, Symfony/League integrations. |
| **Contract** | `Webify\Base\Contract` | Technical utilities and interfaces (e.g. `Collection`, `KeyValueReaderInterface`) that any layer may use. Depends on no layer. |

### Dependency Rules

The following rules are enforced by automated architecture tests
(see [Architecture Tests](#architecture-tests)):

```
┌──────────┐
│ Contract │  ← used by any layer, depends on nothing
└──────────┘

  ┌───────────┐
  │  Domain   │  ← innermost — entities, business rules
  └─────┬─────┘
        │
  ┌─────▼───────┐
  │ Application │  ← thin glue, no infrastructure
  └─────┬───────┘
        │
  ┌─────▼──────────┐
  │ Infrastructure │  ← implements domain & app contracts
  └────────────────┘
```

| Rule | Description |
|------|-------------|
| **Domain** must not depend on **Application** or **Infrastructure** | The domain layer is the innermost ring. It may use **Contract** utilities but must not reference application or infrastructure code. |
| **Application** must not depend on **Infrastructure** | Application interfaces sit between domain and infrastructure. They define contracts (e.g. `ConfigInterface`) without referencing concrete implementations. |
| **Contract** must not depend on any layer | Contract contains purely technical utilities. It must be completely independent of domain, application, and infrastructure code. |
| **Infrastructure** may depend on any layer | Infrastructure implements domain and application interfaces. This is the Dependency Inversion Principle in action. |

### Adding Classes to a Layer

When extending the application or building a new extension, place your classes
in the appropriate namespace:

**Domain layer** — business logic, entities, and domain-specific interfaces:

```php
namespace Webify\Base\Domain\ValueObject;

use Webify\Base\Domain\Service\SomeDomainInterface;

final readonly class SomeValueObject
{
    public function __construct(
        private string $value,
    ) {}

    public function asString(): string
    {
        return $this->value;
    }
}
```

**Application layer** — application-level contracts:

```php
namespace Webify\Base\Application\Service;

interface SomeAppInterface
{
    public function execute(string $input): string;
}
```

**Infrastructure layer** — framework implementations of domain or application contracts:

```php
namespace Webify\Base\Infrastructure\Service;

use Webify\Base\Application\Service\SomeAppInterface;

final readonly class SomeImplementation implements SomeAppInterface
{
    public function execute(string $input): string
    {
        return strtoupper($input);
    }
}
```

**Contract layer** — technical utilities with zero dependencies on other layers:

```php
namespace Webify\Base\Contract;

interface SomeTechnicalInterface
{
    public function transform(mixed $input): mixed;
}
```

### Architecture Tests

Architecture rules live in `test/Architecture/ArchitectureSpec.php` and are
enforced through [phpat](https://github.com/phpat/phpat), running as part of
the PHPStan analysis pipeline:

```bash
vendor/bin/phpstan analyse
```

phpat rules use a fluent API. Example:

```php
public function testDomainDoesNotDependOnApplicationOrInfrastructure(): Rule
{
    return PHPat::rule()
        ->classes(Selector::inNamespace('Webify\Base\Domain'))
        ->shouldNot()
        ->dependOn()
        ->classes(
            Selector::inNamespace('Webify\Base\Application'),
            Selector::inNamespace('Webify\Base\Infrastructure'),
        )
        ->because('Domain is the innermost layer and must not depend on outer layers');
}
```

When you add a new class, ensure it does not introduce unwanted dependencies
between layers. The architecture tests will flag violations.

---

## Directory Structure

```
src/
├── Application/
│   └── Service/
│       └── ConfigInterface.php              ← Application-wide contracts
│
├── Contract/
│   ├── ArraySearchHelper.php               ← Dot-notation array access trait
│   ├── KeyValueReaderInterface.php          ← Read-only key-value contract
│   └── Collection/
│       └── Collection.php                  ← Generic type-safe collection
│
├── Domain/
│   ├── Contract/                           ← Domain-defined interfaces
│   │   ├── Authentication/                 ← Credentials, strategies, ACL
│   │   ├── Authorization/                  ← Resources, subjects, rules
│   │   ├── Identity/                       ← Password hashing
│   │   └── Translation/                    ← i18n-ready exceptions
│   ├── Entity/
│   │   └── AggregateRoot.php               ← Base aggregate with event recording
│   ├── Event/
│   │   ├── DomainEventInterface.php
│   │   └── DomainEventPublisherInterface.php
│   ├── Exception/                          ← Domain-specific exceptions
│   ├── Service/                            ← Domain service contracts
│   │   ├── SlugifyInterface.php
│   │   └── UlidGeneratorInterface.php
│   └── ValueObject/                        ← Immutable self-validating wrappers
│
└── Infrastructure/
    ├── Container/                          ← DI container building
    ├── Contract/                           ← Infrastructure-level contracts
    ├── Environment/                        ← Runtime environment detection
    ├── Event/                              ← Domain event publishing (League)
    ├── Exception/                          ← Infrastructure exceptions
    ├── Kernel/                             ← Entry points (HTTP, Console)
    ├── Presentation/
    │   └── Http/
    │       ├── Controller/                 ← Invokable PSR-7 controllers
    │       └── Middleware/                 ← PSR-15 middleware
    ├── Provider/                           ← Service providers
    ├── Service/                            ← Implementations (config, slug, ULID)
    └── definitions.php                     ← PHP-DI wiring
```

---

## Design Patterns

Each layer follows consistent patterns that make the code predictable and
easy to extend.

### Domain Layer

| Pattern | Where | Description |
|---------|-------|-------------|
| **Value Object** | `Domain\ValueObject\*` | Immutable wrappers that validate on construction. `Email`, `Slug`, `AggregateId`, `DateTime`, `SecureToken` all enforce their own invariants and expose behaviour — not just getters. |
| **Aggregate** | `Domain\Entity\AggregateRoot` | Base entity that records domain events during a transaction and releases them for publishing after the transaction completes. |
| **Domain Event** | `Domain\Event\*` | Immutable records of something that happened in the domain (`DomainEventInterface`). Published via `DomainEventPublisherInterface` so side effects are decoupled from business logic. |
| **Factory Method** | `Domain\Exception\*` | Static named constructors replace guesswork. Exceptions like `AccessDeniedException::deniedFor()` and `DateTimeException::forInvalidDatetime()` encode intent directly in the call. |
| **Strategy** | `Domain\Contract\Authentication\*` | Authentication strategies (`PasswordBaseInterface`, `ChallengeBasedInterface`) are interchangeable algorithms selected at runtime through `StrategyInterface`. |
| **Anti-Corruption Layer** | `Domain\Contract\Authentication\UserCredentialsLookupInterface` | Shields the domain from external user-store implementations by defining a narrow, domain-specific interface. |
| **Translation DTO** | `Domain\Contract\Translation\*` | `ExceptionTranslation` carries a group, key, and parameters so exceptions can produce translated messages without coupling to any i18n library. |

### Application Layer

| Pattern | Where | Description |
|---------|-------|-------------|
| **Service Interface** | `Application\Service\*` | Thin contracts like `ConfigInterface` that abstract infrastructure details behind a stable, non-framework API. |

### Infrastructure Layer

| Pattern | Where | Description |
|---------|-------|-------------|
| **Adapter** | `Infrastructure\Service\*` | Wraps library classes behind domain interfaces. `SymfonyAsciiSlugify` → `SlugifyInterface`, `SymfonyUlidGenerator` → `UlidGeneratorInterface`, `LeagueDomainEventPublisher` → `DomainEventPublisherInterface`. |
| **Service Provider** | `Infrastructure\Provider\*` | Two-phase registration: `getDefinitions()` adds container bindings, `bootstrap()` wires routes, middleware, and commands after the container is built. |
| **Middleware** | `Infrastructure\Presentation\Http\Middleware\*` | Each PSR-15 middleware handles one cross-cutting concern. `ErrorHandler` catches all uncaught exceptions — re-throwing in debug mode, logging and returning an error response in production. |
| **Builder** | `Infrastructure\Container\PhpDiContainerBuilder` | Encapsulates the construction of a fully configured PHP-DI container: autowiring, compilation mode, provider loading, and extension discovery. |
| **Invokable Controller** | `Infrastructure\Presentation\Http\Controller\*` | Controllers are single-action `__invoke(ServerRequestInterface): ResponseInterface` classes. Their only dependency is the PSR-17 factory, injected via constructor (see [Controllers](#controllers)). |
| **Dependency Injection** | `Infrastructure\definitions.php` | Central PHP-DI definitions file that wires all PSR-7 factories, the router, logger, event dispatcher, domain event publisher, console application, and kernels. |

### Contract Layer

| Pattern | Where | Description |
|---------|-------|-------------|
| **Collection** | `Contract\Collection\Collection` | Generic abstract collection with type-safe `add`, `map`, `filter`, `reduce`, `merge`, `find`, `contains`, and iteration. The type of contained items is enforced in the concrete subclass. |
| **Key-Value Reader** | `Contract\KeyValueReaderInterface` | Read-only key-value access (`has`, `get`) that keeps callers decoupled from the storage mechanism. |
| **Array Search Helper** | `Contract\ArraySearchHelper` | Reusable trait for recursive dot-notation array lookups. Used by the `Config` implementation. |

---

## Infrastructure

This section covers the concrete infrastructure components you interact with
when bootstrapping and running the application.

### Environment Variables

Sensitive and environment-specific values are managed through `.env` files
using [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv). The `.env` file
is loaded **before** the config array, so `$_ENV` values are available when
`config/config.php` is evaluated:

```php
// public/index.php
Dotenv::createImmutable(__DIR__ . '/..')->load();
```

The supported variables are documented in `.env.example`:

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | `WebifyCMS` | Application name |
| `APP_ID` | `webifycms` | Application identifier |
| `APP_VERSION` | `0.0.1` | Application version |
| `APP_ENV` | `development` | Runtime environment (`production` or `development`) |
| `APP_DEBUG` | `true` | Enable/disable debug mode |

Copy `.env.example` to `.env` and adjust as needed:

```bash
cp .env.example .env
```

> **Note:** `.env` is gitignored. Never commit secrets or environment-specific
> values to the repository.

> **Important:** Don't try to read an environment variable outside the config file,
> it should be read from the config array only. 

### Configuration

The `ConfigInterface` (`Webify\Base\Application\Service\ConfigInterface`)
defines the application configuration contract using PHP 8.4 property hooks:

```php
interface ConfigInterface extends KeyValueReaderInterface
{
    public string $basePath { get; }
    public string $runtimePath { get; }
    public string $configPath { get; }
    public string $cachePath { get; }
    public string $logPath { get; }
}
```

The concrete implementation `Webify\Base\Infrastructure\Service\Config`
accepts an associative array and computes `cachePath` and `logPath`
from `runtimePath`:

```php
use Webify\Base\Infrastructure\Service\Config;

$config = new Config([
    'basePath'    => '/var/www/app',
    'runtimePath' => '/var/www/app/var',
    'configPath'  => '/var/www/app/config',
    'id'          => 'my-app',
]);
```

The config also supports arbitrary key-value storage via
`get(string $key, mixed $default = null): mixed` and
`has(string $key): bool`, inherited from `KeyValueReaderInterface`.

### Environment

The environment is managed through `Webify\Base\Infrastructure\Environment\Environment`,
a final readonly class built via the static factory `prepare()`:

```php
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Environment\Type;

$environment = Environment::prepare($config);

$environment->isProduction();   // true when Type::Production
$environment->isDevelopment();  // true when Type::Development
$environment->isDebugEnabled(); // controlled by config key 'debug'
```

The config must contain an `environment` key matching one of the `Type` enum
values (`'production'` or `'development'`). The `debug` key (boolean) enables
verbose error output.

By default these values are sourced from the `.env` file via `$_ENV['APP_ENV']`
and `$_ENV['APP_DEBUG']`. Sensible defaults are defined in `config/config.php`,
so the application works out of the box without a `.env` file during development.

### Application Lifecycle

`Webify\Base\Infrastructure\Service\Application` is the central bootstrap
class. Its lifecycle follows three phases:

1. **Instantiation** — validate paths and create runtime directories
2. **Bootstrap** — build the DI container and run bootstrap providers
3. **Run** — dispatch the HTTP request or console command

```php
use Webify\Base\Infrastructure\Service\Application;
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Service\Config;
use Webify\Base\Infrastructure\Container\PhpDiContainerBuilder;

$config      = new Config([/* ... */]);
$environment = Environment::prepare($config);
$app         = new Application($config, $environment);

// Register providers before bootstrapping
$app->registerProvider(new MyServiceProvider());

// Bootstrap: builds the container, calls bootstrap() on all providers
$app->bootstrap(new PhpDiContainerBuilder());

// Handle HTTP request or console command
$app->run();         // HTTP
$app->runConsole();  // CLI
```

### Container Building

The container is built by implementations of `ContainerBuilderInterface`.
The default is `PhpDiContainerBuilder`, which uses PHP-DI:

- Autowiring is enabled
- `ConfigInterface` and `Environment` are pre-registered as shared services
- Provider definitions are loaded from the `providers` config array
- Extension providers are loaded from the `extensions` config array
- In production mode, compilation and proxy file generation are enabled,
  writing to the cache path defined in config

```php
use Webify\Base\Infrastructure\Container\PhpDiContainerBuilder;

$app->bootstrap(new PhpDiContainerBuilder());
```

After bootstrapping, the PSR-11 container is available via:

```php
$container = $app->getContainer();
$router    = $container->get(Router::class);
```

### Service Providers

Service providers allow extensions to register container definitions and
perform bootstrapping logic. Two interfaces are available:

Providers can reach the application in two ways:

1. **Direct registration** — passed as an instance to `$app->registerProvider()`.
   The provider may have any constructor dependencies because you construct it
   yourself:

   ```php
   $app->registerProvider(new MyProvider($someDependency));
   ```

2. **Config autodiscovery** — listed in the `providers` config array as a
   class-string. The container builder instantiates them with `new $class()`.
   These providers **must have a parameterless constructor** (or no explicit
   constructor at all) since the builder does not resolve their dependencies
   from the container.

   ```php
   $config = new Config([
       'providers' => [
           MyProvider::class, // must work with new MyProvider()
       ],
   ]);
   ```

   The same applies to providers returned by an extension's `getProviders()` —
   they are also instantiated with `new $class()` and must have no required
   constructor parameters.

If a provider needs container services, it pulls them from the `ContainerInterface`
argument passed to `bootstrap()` — not from its own constructor.

**`ServiceProviderInterface`** — provides container definitions before
the container is built:

```php
use Webify\Base\Infrastructure\Contract\ServiceProviderInterface;

final class MyProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            'my.service' => DI\autowire(MyService::class),
        ];
    }
}
```

**`BootstrapServiceProviderInterface`** — performs bootstrapping after
the container is built (routes, middleware, commands, listeners):

```php
use Webify\Base\Infrastructure\Contract\BootstrapServiceProviderInterface;
use Psr\Container\ContainerInterface;
use League\Route\Router;

final class MyBootstrapProvider implements BootstrapServiceProviderInterface
{
    public function bootstrap(ContainerInterface $container): void
    {
        $router = $container->get(Router::class);
        $router->map('GET', '/hello', new HelloController());
    }
}
```

A single class may implement both interfaces. The base extension's
`BaseServiceProvider` does exactly this:

```php
final readonly class BaseServiceProvider implements
    ServiceProviderInterface,
    BootstrapServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return require __DIR__ . '/../definitions.php';
    }

    public function bootstrap(ContainerInterface $container): void
    {
        $router       = $container->get(Router::class);
        $errorHandler = $container->get(ErrorHandler::class);

        $router->middleware($errorHandler);
    }
}
```

Providers are registered before bootstrapping:

```php
$app->registerProvider(new BaseServiceProvider());
$app->registerProvider(new MyProvider());
$app->bootstrap(new PhpDiContainerBuilder());
```

Providers listed in the config `providers` array are automatically
instantiated and registered during container building:

```php
$config = new Config([
    'providers' => [
        MyProvider::class,
    ],
]);
```

### Extensions

Extensions represent reusable packages that bundle multiple service providers.
They implement `ExtensionInterface`:

```php
use Webify\Base\Infrastructure\Contract\ExtensionInterface;
use Webify\Base\Infrastructure\Contract\ServiceProviderInterface;

final class MyExtension implements ExtensionInterface
{
    public function getId(): string
    {
        return 'my_extension';
    }

    public function getName(): string
    {
        return 'My Extension';
    }

    /** @return array<class-string<ServiceProviderInterface>> */
    public function getProviders(): array
    {
        return [
            MyExtensionServiceProvider::class,
        ];
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }
}
```

Extensions are registered via the `extensions` config array:

```php
$config = new Config([
    'extensions' => [
        MyExtension::class,
    ],
]);
```

During container building, each extension's providers are instantiated,
registered, and their definitions added to the container — just like
directly registered providers.

### Kernels

Two kernels handle the application's entry points.

**HTTP Kernel** (`Webify\Base\Infrastructure\Kernel\Http`) creates a PSR-7
server request from PHP globals, dispatches it through the League router,
and emits the response. Unmatched routes (404) receive a 302 redirect to
`/404`:

```php
// Registered automatically as 'httpKernel' in definitions.php
$app->run();
```

**Console Kernel** (`Webify\Base\Infrastructure\Kernel\Console`) wraps
the Symfony Console application and runs it:

```php
// Registered automatically as 'consoleKernel' in definitions.php
$app->runConsole(); // returns exit code
```

### Controllers

Controllers live in `Infrastructure\Presentation\Http\Controller\` and follow
a consistent pattern:

- **Single action** — each controller is an invokable class (`__invoke`).
  No `IndexController` with five methods; use one class per route.
- **Readonly** — controllers are `final readonly class` instances.
- **PSR-7 in, PSR-7 out** — they receive `ServerRequestInterface` and return
  `ResponseInterface`.
- **Minimal dependencies** — the only injected dependency is a PSR-17 factory
  (usually `Psr17Factory`) so they can create responses and streams.

```php
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

final readonly class Home
{
    public function __construct(
        private Psr17Factory $factory,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->factory->createResponse(200);
        $body     = $this->factory->createStream('Hello, world!');

        return $response
            ->withBody($body)
            ->withHeader('Content-Type', 'text/plain')
        ;
    }
}
```

Controllers are registered as routes inside a `BootstrapServiceProvider`:

```php
public function bootstrap(ContainerInterface $container): void
{
    $router = $container->get(Router::class);
    $router->map('GET', '/', [Home::class, '__invoke']);
}
```

The PSR-17 factory is autowired by the container, so you never need to register
it — just type-hint it in the controller's constructor.

### Error Handling Middleware

`Webify\Base\Infrastructure\Presentation\Http\Middleware\ErrorHandler` is a
PSR-15 middleware that wraps the entire middleware pipeline:

- **Debug mode** (`isDebugEnabled()` returns `true`): re-throws the exception
  so a development tool like Whoops can render a diagnostic page.
- **Production mode**: catches the exception, logs server errors (500+)
  with full context, and returns an appropriate HTTP error response with
  `Content-Type: text/html; charset=utf-8`.
- HTTP exceptions (implementing `HttpExceptionInterface`) use their status
  code; all others default to 500.

```php
$router->middleware($errorHandler); // registered by BaseServiceProvider
```

### Complete Bootstrap Example

```php
use Dotenv\Dotenv;
use Webify\Base\Infrastructure\Container\PhpDiContainerBuilder;
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Provider\BaseServiceProvider;
use Webify\Base\Infrastructure\Service\Application;
use Webify\Base\Infrastructure\Service\Config;

// 0. Load environment variables from .env (optional — falls back to defaults)
Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

// 1. Configuration (sourced from $_ENV via config/config.php)
$config = new Config([
    'basePath'    => __DIR__ . '/..',
    'runtimePath' => __DIR__ . '/../var',
    'configPath'  => __DIR__ . '/../config',
    'providers'   => [
        MyProvider::class,
    ],
    'extensions'  => [
        MyExtension::class,
    ],
]);

// 2. Environment
$environment = Environment::prepare($config);

// 3. Application
$app = new Application($config, $environment);

// 4. Register core providers
$app->registerProvider(new BaseServiceProvider());
$app->registerProvider(new RouteServiceProvider());

// 5. Bootstrap (builds container, runs bootstrap providers)
$app->bootstrap(new PhpDiContainerBuilder());

// 6. Run
$app->run();
```

### Development Server

During development you can use PHP's built-in web server:

```bash
php -S localhost:8000 -t public/
```

The application is now available at `http://localhost:8000`.

> **Note:** The built-in server is single-threaded and intended for development
> only. Use a dedicated web server (Nginx, Caddy, etc.) in production.

## Architecture Tests

Architecture rules are enforced by **phpat** as part of the PHPStan analysis
pipeline. Run them with:

```bash
vendor/bin/phpstan analyse
```

The `ArchitectureSpec` class in `test/Architecture/` defines all rules.
Tests follow a fluent pattern:

```php
public function testRuleName(): Rule
{
    return PHPat::rule()
        ->classes(Selector::inNamespace('Webify\Base\Domain'))
        ->shouldNot()
        ->dependOn()
        ->classes(Selector::inNamespace('Webify\Base\Infrastructure'))
        ->because('description of the rule');
}
```

Adding a new rule is straightforward — create a new method returning `Rule`
in `ArchitectureSpec.php`.
