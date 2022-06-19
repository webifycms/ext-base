<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;

/**
 * FileNotExistException
 */
class FileNotExistException extends TranslatableException
{
    /**
     * The class constructor.
     *
     * @param string $messageKey
     * @param string[] $params
     */
    public function __construct(
        $messageKey = 'file_not_exist',
        $params = []
    ) {
        parent::__construct($messageKey, $params);
    }
}
