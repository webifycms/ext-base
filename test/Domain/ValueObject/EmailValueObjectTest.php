<?php
declare(strict_types=1);

namespace OneCMS\Base\Test\Domain\ValueObject;

use OneCMS\Base\Domain\Exception\InvalidEmailAddressException;
use OneCMS\Base\Domain\ValueObject\EmailValueObject;
use PHPUnit\Framework\TestCase;

final class EmailValueObjectTest extends TestCase
{
    public function testCanBeCreatedWithValidEmailAddress(): void
    {
        $this->assertInstanceOf(
            EmailValueObject::class,
            new EmailValueObject('test@example.com')
        );
    }

    public function testCannotBeCreatedWithInvalidEmailAddress(): void
    {
        $this->expectException(InvalidEmailAddressException::class);

        (new EmailValueObject('invalid_email'));
    }
}
