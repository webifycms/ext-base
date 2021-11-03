<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use OneCMS\Base\Domain\Exception\InvalidDatetimeException;

/**
 * Class DateTime
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class DateTime
{
    /**
     * The default datetime format for the output.
     */
    const DEFAULT_FORMAT = DateTimeImmutable::W3C;

    /**
     * @var DateTimeInterface|DateTimeImmutable
     */
    private DateTimeInterface $value;

    /**
     * @param string $datetime
     */
    public function __construct(string $datetime = 'now')
    {
        try {
            $this->value = new DateTimeImmutable($datetime, $this->getTimeZone());
        } catch (\Throwable $throwable) {
            throw new InvalidDatetimeException();
        }
    }

    /**
     * @return DateTimeZone
     */
    public function getTimeZone(): DateTimeZone
    {
        return new DateTimeZone(date_default_timezone_get());
    }

    /**
     * @return DateTimeInterface
     */
    public function getValue(): DateTimeInterface
    {
        return $this->value;
    }

    /**
     * @param string|null $format
     * @return string
     */
    public function getFormattedValue(?string $format = null): string
    {
        return $this->value->format($format ?? self::DEFAULT_FORMAT);
    }
}