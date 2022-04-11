<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;

/**
 * FileNotExistException
 */
class FileNotExistException extends TranslatableException
{
    public function __construct(string $messageKey = 'file_not_exist', array $params = [])
    {
        parent::__construct($messageKey, $params);
    }
}
