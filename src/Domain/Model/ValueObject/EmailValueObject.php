<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model\ValueObject;

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
     * @var string
     */
    private string $value;

    /**
     * EmailAddress constructor.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->validate($email);

        $this->value = $email;
    }

    /**
     * @param string $email
     */
    private function validate(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException();
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
