<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Persistence\Service;

use OneCMS\Base\Domain\Exception\ValidationFailedException;

/**
 * Class DatabaseIdException
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class DatabaseIdException extends ValidationFailedException
{

    /**
     * DatabaseIdException constructor.
     *
     * @param string $messageKey
     */
    public function __construct(string $messageKey = 'invalid_database_id')
    {
        parent::__construct($messageKey);
    }
}
