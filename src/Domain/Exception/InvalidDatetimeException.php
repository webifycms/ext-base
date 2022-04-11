<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;
/**
 * InvalidDatetimeException
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class InvalidDatetimeException extends TranslatableException
{
    public function __construct(string $messageKey = 'invalid_datetime', array $params = [])
    {
        parent::__construct($messageKey, $params);
    }
}
