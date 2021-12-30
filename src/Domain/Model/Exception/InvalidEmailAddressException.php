<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;

use OneCMS\Base\Domain\Exception\ValidationFailedException;

/**
 * Class InvalidEmailAddressException
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class InvalidEmailAddressException extends ValidationFailedException
{
    /**
     * InvalidEmailAddressException constructor.
     *
     * @param string $messageKey
     */
    public function __construct(string $messageKey = 'invalid_email')
    {
        parent::__construct($messageKey);
    }
}
