<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Library\Uuid;


use OneCMS\Base\Domain\Exception\ValidationFailedException;

/**
 * Class UuidInvalidVersionException
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class InvalidUuidException extends ValidationFailedException
{
    /**
     * UuidInvalidVersionException constructor.
     *
     * @param string $messageKey
     */
    public function __construct(string $messageKey = 'invalid_uuid')
    {
        parent::__construct($messageKey);
    }
}