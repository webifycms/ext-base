<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Validation;

use InvalidArgumentException;

/**
 * Class ValidationFailedException
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class ValidationFailedException extends InvalidArgumentException implements ValidationFailedExceptionInterface
{
    /**
     * ValidationFailedException constructor.
     */
    public function __construct(private readonly string $messageKey, private readonly array $params = [])
    {
        parent::__construct();
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
