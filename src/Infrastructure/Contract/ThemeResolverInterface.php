<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Contract;

use Webify\Base\Domain\ValueObject\TemplatePath;

/**
 * Contract for resolving the effective themes from the app configuration.
 *
 * Services should depend on this contract instead of injecting `ThemeInterface`
 * directly, since a single `ThemeInterface` cannot represent the default-vs-active
 * fallback distinction.
 */
interface ThemeResolverInterface
{
	/**
	 * Returns the currently active theme, falling back to the default theme
	 * when no active theme is configured.
	 */
	public function active(): ThemeInterface;

	/**
	 * Returns the default theme.
	 */
	public function default(): ThemeInterface;

	/**
	 * Returns the effective themes in resolution order: the default theme first,
	 * then the active theme when it differs, so the active theme can override.
	 *
	 * @return list<ThemeInterface>
	 */
	public function themes(): array;

	/**
	 * Returns the template paths of the effective themes, default first and
	 * active last, so the active theme can override the default theme.
	 *
	 * @return list<string>
	 */
	public function themeTemplatePaths(): array;

	/**
	 * Returns the template maps of the effective themes, default first and
	 * active last, so the active theme can override the default theme.
	 *
	 * @return list<array<string, TemplatePath>>
	 */
	public function themeTemplateMaps(): array;
}
