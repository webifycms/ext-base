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
    const DEFAULT_FORMAT = DateTimeImmutable::W3C;

    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $datetime = null;

    /**
     * @param string $datetime
     * @param DateTimeZone $timeZone
     */
    public function __construct(string $datetime = 'now', DateTimeZone $timeZone = null)
    {
        try {
            $this->datetime = new DateTimeImmutable($datetime, $timeZone ?? $this->getTimeZone());
        } catch (\Throwable $throwable) {
            throw new InvalidDatetimeException();
        }
    }

    /**
     * @return DateTimeZone
     */
    public function getTimeZone(): DateTimeZone
    {
        if ($this->datetime instanceof DateTimeInterface) {
            return $this->datetime->getTimezone();
        }

        return new DateTimeZone(date_default_timezone_get());
    }

    /**
     * @return DateTimeInterface
     */
    public function getDateTime(): DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * @param string|null $format
     * @return string
     */
    public function getFormattedValue(?string $format = null): string
    {
        return $this->datetime->format($format ?? self::DEFAULT_FORMAT);
    }
}
