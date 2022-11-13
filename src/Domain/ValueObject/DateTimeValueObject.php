<?php
/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use OneCMS\Base\Domain\Exception\InvalidDatetimeException;

/**
 * It's a value object that holds a datetime object and provides a method
 * to get the formatted datetime as string.
 */
final class DateTimeValueObject
{
    /**
     * The default datetime format for the output.
     */
    public const DEFAULT_FORMAT = \DateTimeImmutable::W3C;

    private \DateTimeInterface $datetime;

    /**
     * The object constructor.
     *
     * @param string $datetimeString default is 'now'
     * @param string $format         default value is \DateTimeImmutable::W3C
     */
    public function __construct(
        string $datetimeString = 'now',
        string $format = self::DEFAULT_FORMAT
    ) {
        $datetime = \DateTimeImmutable::createFromFormat($format, $datetimeString);

        if (!$datetime) {
            throw new InvalidDatetimeException('invalid_datetime', ['datetime' => $datetimeString]);
        }

        $this->datetime = $datetime;
    }

    /**
     * Returns the datetime object.
     */
    public function getDateTime(): \DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * It returns a string representation of the object.
     *
     * @return string The date and time in the format of Y-m-d\\TH:i:sP, eg: 2005-08-15T15:52:01+00:00
     */
    public function __toString(): string
    {
        return $this->datetime->format(self::DEFAULT_FORMAT);
    }
}
