# WebifyCMS Base Extension (shared-kernel)

Package `webifycms/ext-base` (composer type `webifycms-extension`). The foundation
layer of WebifyCMS. Every other extension depends on it. It is NOT a standalone
application — it provides the bootstrap lifecycle, domain building blocks, the
infrastructure stack, and the contracts that make extensions composable.

## Layout

```
src/Application/        # application-layer service contracts (ConfigInterface)
src/Contract/           # shared contracts: Collection, Translation, Exception, helpers
src/Domain/
  Contract/             # Authorization, Authentication, Identity
  Entity/AggregateRoot.php
  Event/                # DomainEventInterface, DomainEventPublisherInterface
  Exception/
  Service/              # SlugifyInterface, UlidGeneratorInterface
  ValueObject/          # AggregateId, Email, Slug, DateTime, SecureToken
src/Infrastructure/
  Container/            # PhpDiContainerBuilder
  Container/Definitions/# Console, Event, Http, Logging definition groups
  Contract/             # ExtensionInterface, ServiceProviderInterface, bootstrap,
                        # definition groups, error handler, escaper
  Environment/
  Event/                # LeagueDomainEventPublisher (PSR-14 bridge)
  Helper/               # Escaper
  Kernel/               # Http + Console kernels
  Provider/             # BaseServiceProvider
  Service/              # Application, Config, SymfonyAsciiSlugify, SymfonyUlidGenerator
```

## Conventions

- Namespace `Webify\Base\*` (autoload `Webify\Base\` → `src/`); tests `Webify\Base\Test\` → `test/`.
- Two provider styles: `ServiceProviderInterface::getDefinitions()` (pre-build) and
  `BootstrapServiceProviderInterface::bootstrap()` (post-build).
- Extension entry points implement `ExtensionInterface`.
- ARCHITECTURE IS ENFORCED by PHPat (`test/Architecture/ArchitectureSpec.php`):
  dependency direction must be Domain → Application → Infrastructure.
- VOs immutable + `fromNative()`/`toNative()` (string-backed enums allowed); aggregates extend `AggregateRoot`.

## Notes

- The theming system (template resolution, theme inheritance, rendering,
  assets) is designed but **not implemented** in this package yet — see
  `docs/theming/architecture.md` for the design pending contract implementation.

## Verification

```bash
composer test          # PHPUnit (incl. architecture spec)
composer analyse       # PHPStan level 8
composer sniff         # PHP-CS-Fixer dry-run
composer code-check    # analyse + sniff
composer code-format   # PHP-CS-Fixer fix
```