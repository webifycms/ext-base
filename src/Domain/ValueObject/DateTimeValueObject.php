<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use Throwable;
use Webify\Base\Domain\Exception\InvalidDatetimeException;

/**
 * It's a value object that holds a datetime object and provides a method
 * to get the formatted datetime as string.
 */
final class DateTimeValueObject
{
	/**
	 * The format for the output defaults to 'Y-m-d H:i:s'.
	 */
	public const DEFAULT_FORMAT = 'Y-m-d H:i:s';

	private DateTimeInterface $datetime;

	private string $format;

	/**
	 * The object constructor.
	 */
	private function __construct(
		DateTimeInterface|string $datetime,
		?string $format = null
	) {
		if (is_string($datetime)) {
			try {
				$datetime = new DateTimeImmutable($datetime);
			} catch (Throwable $exception) {
				throw new InvalidDatetimeException(
					InvalidDatetimeException::MESSAGE_KEY,
					['datetime' => $datetime],
					$exception->getCode(),
					$exception
				);
			}
		}

		$this->datetime = $datetime;
		$this->format   = $format ?? self::DEFAULT_FORMAT;
	}

	/**
	 * It returns a string representation of the object.
	 *
	 * @return string The datetime in the given format or default format Y-m-d H:i:s, eg: 2020-01-01 00:00:00
	 */
	public function __toString(): string
	{
		return $this->datetime->format($this->format);
	}

	/**
	 * Creates datetime value object from the given datetime string or object.
	 */
	public static function create(DateTimeInterface|string $datetime = 'now'): self
	{
		return new self($datetime);
	}

	/**
	 * Creates datetime value object from the given format and datetime strings.
	 */
	public static function createFromFormat(string $format, string $datetime): self
	{
		$datetimeObj = DateTimeImmutable::createFromFormat($format, $datetime);

		if (false === $datetimeObj) {
			throw new InvalidDatetimeException(
				'base.invalid_format_or_datetime',
				[
					'datetime' => $datetime,
					'format'   => $format,
				]
			);
		}

		return new self($datetimeObj, $format);
	}

	/**
	 * Returns the datetime object.
	 */
	public function getDateTimeObject(): DateTimeInterface
	{
		return $this->datetime;
	}
}
