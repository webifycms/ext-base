<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use OneCMS\Base\Domain\Exception\InvalidDatetimeException;

/**
 * Class DateTimeValueObject
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class DateTimeValueObject
{
    /**
     * The default datetime format for the output.
     */
    public const DEFAULT_FORMAT = DateTimeImmutable::W3C;

    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $datetime;

    /**
     * The class constructor.
     *
     * @param string $datetimeString
     * @param DateTimeZone $timezone
     */
    public function __construct(
        $datetimeString = 'now',
        DateTimeZone $timezone = new DateTimeZone(date_default_timezone_get())
    ) {
        try {
            $this->datetime = new DateTimeImmutable($datetimeString, $timezone);
        } catch (\Throwable) {
            throw new InvalidDatetimeException('invalid_datetime', ['datetime' => $datetimeString]);
        }
    }

    /**
     * Returns the default timezone object.
     *
     * @return DateTimeZone
     */
    public static function getDefaultTimeZone(): DateTimeZone
    {
        return new DateTimeZone(date_default_timezone_get());
    }

    /**
     * Returns the datetime object.
     *
     * @return DateTimeInterface
     */
    public function getDateTime(): DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * Returns the formatted datetime as string.
     *
     * @param string|null $format if format is null will use default format.
     * @return string
     */
    public function getFormattedValue(?string $format = null): string
    {
        return $this->datetime->format($format ?? self::DEFAULT_FORMAT);
    }
}
