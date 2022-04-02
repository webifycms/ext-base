<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Model\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use OneCMS\Base\Domain\Model\Exception\InvalidDatetimeException;

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

    public function __construct(string $datetime = 'now', ?DateTimeZone $timeZone = null)
    {
        try {
            $this->datetime = new DateTimeImmutable($datetime, $timeZone ?? self::getDefaultTimeZone());
        } catch (\Throwable) {
            throw new InvalidDatetimeException('invalid_datetime', ['datetime' => $datetime]);
        }
    }

    public static function getDefaultTimeZone(): DateTimeZone
    {
        return new DateTimeZone(date_default_timezone_get());
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->datetime;
    }

    public function getFormattedValue(?string $format = null): string
    {
        return $this->datetime->format($format ?? self::DEFAULT_FORMAT);
    }
}
