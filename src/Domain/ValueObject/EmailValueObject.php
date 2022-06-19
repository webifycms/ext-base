<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use OneCMS\Base\Domain\Exception\InvalidEmailAddressException;

/**
 * EmailValueObject class
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class EmailValueObject
{
    /**
     * EmailAddress constructor.
     */
    public function __construct(
        private readonly string $email
    ) {
        $this->validate($email);
    }

    /**
     * Validates the given email string and throws an exception if validation failed.
     *
     * @param string $email
     * @throws InvalidEmailAddressException
     */
    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException('invalid_email', ['email' => $email]);
        }
    }

    /**
     * Returns email string.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
