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

namespace Webify\Base\Domain\Exception;

use Webify\Base\Domain\Service\Exception\TranslatableExceptionServiceInterface;

/**
 * Represents a translatable invalid argument exception that includes a
 * translatable message key and associated parameters.
 *
 * This exception is designed to provide additional context for messaging systems by
 * including a message key for translation and an array of parameters that can be used
 * to populate the message dynamically.
 */
class TranslatableInvalidArgumentException extends \InvalidArgumentException
    implements TranslatableExceptionServiceInterface
{
    /**
     * The class constructor.
     *
     * @param string[] $params additional items that should be included in the message can be
     *                         passed as `name => value` pairs.
     */
    public function __construct(
        public readonly string      $messageKey,
        public readonly array       $params = [],
        public                      $code = 0,
        public readonly ?\Throwable $previous = null
    )
    {
        parent::__construct('', $code, $previous);
    }

    /**
     * @inheritDoc
     */
    public function getMessageKey(): string
    {
        return $this->messageKey;
    }

    /**
     * @inheritDoc
     */
    public function getParam(string $key): ?string
    {
        return $this->params[$key] ?? null;
    }
}
