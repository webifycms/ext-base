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

namespace Webify\Base\Test\Architecture;

use Exception;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

/**
 * Architecture tests that enforce DDD and clean architecture principles.
 *
 * Domain is the innermost layer — it must not know about Application or Infrastructure.
 * Application sits between Domain and Infrastructure.
 * Contract is a shared kernel that can be referenced by all layers.
 * Infrastructure implements Domain interfaces (Dependency Inversion).
 *
 * @internal
 */
final class ArchitectureSpec
{
	/**
	 * Domain classes must not depend on Application or Infrastructure.
	 * Dependency on Contract (shared kernel) is permitted.
	 */
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
			->because('Domain is the innermost layer and must not depend on outer layers')
		;
	}

	/**
	 * Contract must not depend on Application or Infrastructure.
	 * It may reference Domain types.
	 */
	public function testContractDoesNotDependOnApplicationOrInfrastructure(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('Webify\Base\Contract'))
			->shouldNot()
			->dependOn()
			->classes(
				Selector::inNamespace('Webify\Base\Application'),
				Selector::inNamespace('Webify\Base\Infrastructure'),
			)
			->because('Contract must not depend on outer layers')
		;
	}

	/**
	 * Application classes must not depend on Infrastructure classes.
	 */
	public function testApplicationDoesNotDependOnInfrastructure(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('Webify\Base\Application'))
			->shouldNot()
			->dependOn()
			->classes(Selector::inNamespace('Webify\Base\Infrastructure'))
			->because('Application should not depend on Infrastructure details')
		;
	}

	/**
	 * All domain exception classes must extend \Exception.
	 */
	public function testDomainExceptionsExtendException(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('Webify\Base\Domain\Exception'))
			->should()
			->extend()
			->classes(Selector::classname(Exception::class))
			->because('All domain exceptions must extend \Exception to ensure consistent error handling')
		;
	}

	/**
	 * All infrastructure exception classes must extend \Exception.
	 */
	public function testInfrastructureExceptionsExtendException(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('Webify\Base\Infrastructure\Exception'))
			->should()
			->extend()
			->classes(Selector::classname(Exception::class))
			->because('All infrastructure exceptions must extend \Exception')
		;
	}
}
