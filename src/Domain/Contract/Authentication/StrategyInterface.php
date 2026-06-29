<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\Contract\Authentication;

/**
 * StrategyInterface defines the contract for authentication strategies.
 *
 * This is the single seam for all authentication mechanisms — built-in (password, code, link)
 * and third-party authenticators.
 *
 * A strategy should cover only the INITIATION step of authentication.
 *
 * Third-party integrators register a concrete implementation through their extension's service provider.
 */
interface StrategyInterface
{
	/**
	 * Returns the unique identifier of the strategy.
	 *
	 * 1. This identifier is used to identify the strategy from the registry.
	 * 2. It is also used to identify the strategy in the authentication request.
	 */
	public function getIdentifier(): string;

	/**
	 * Checks if the strategy supports the given credentials.
	 */
	public function isSupported(CredentialsInterface $credentials): bool;

	/**
	 * Initiate the authentication for the user based on the provided credentials.
	 *
	 * @param CredentialsInterface $requestCredentials the credentials used for authentication request
	 */
	public function initiate(
		CredentialsInterface $requestCredentials,
		UserCredentials $userCredentials
	): void;
}
