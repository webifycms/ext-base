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

namespace Webify\Base\Infrastructure\Contract;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Throwable;

/**
 * ErrorHandlerInterface defines the contract for handling errors during the processing of a server request.
 *
 * Defines a method to manage and respond to exceptions or errors that occur
 * within the context of handling an HTTP request.
 */
interface ErrorHandlerInterface
{
	/**
	 * Handles the incoming request and throwable exception and generates an appropriate response.
	 *
	 * @param ServerRequestInterface $request   the server request instance
	 * @param Throwable              $throwable the throwable exception to handle
	 *
	 * @return ResponseInterface the generated response after handling the request and exception
	 */
	public function handle(ServerRequestInterface $request, Throwable $throwable): ResponseInterface;
}
