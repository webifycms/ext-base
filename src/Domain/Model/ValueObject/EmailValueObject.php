<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model\ValueObject;

use OneCMS\Base\Domain\Model\Exception\InvalidEmailAddressException;

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
     * @var string
     */
    private readonly string $email;

    /**
     * EmailAddress constructor.
     */
    public function __construct(string $email)
    {
        $this->validate($email);

        $this->email = $email;
    }

    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException('invalid_email', ['email' => $email]);
        }
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
