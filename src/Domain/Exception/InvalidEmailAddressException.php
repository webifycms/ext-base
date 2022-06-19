<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;

/**
 * Class InvalidEmailAddressException
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class InvalidEmailAddressException extends TranslatableException
{
    /**
     * The class constructor.
     *
     * @param string $messageKey
     * @param string[] $params
     */
    public function __construct(
        $messageKey = 'invalid_email',
        $params = []
    ) {
        parent::__construct($messageKey, $params);
    }
}
