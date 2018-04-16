<?php

/*
 * PHP-Temporal (https://github.com/delight-im/PHP-Temporal)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Temporal;

use Delight\Temporal\Iso8601\Iso8601Duration;
use Delight\Temporal\Throwable\Error;
use Delight\Temporal\Throwable\IllegalDurationOperationError;
use Delight\Temporal\Throwable\InvalidDurationComponentError;
use Delight\Temporal\Throwable\InvalidDurationFormatError;

/** Immutable duration of date and/or time */
final class Duration {

	/** @internal */
	const PROPERTIES = [
		self::PROPERTIES_KEY_DATE => [
			'years' => Iso8601Duration::SUFFIX_YEARS,
			'months' => Iso8601Duration::SUFFIX_MONTHS,
			'weeks' => Iso8601Duration::SUFFIX_WEEKS,
			'days' => Iso8601Duration::SUFFIX_DAYS
		],
		self::PROPERTIES_KEY_TIME => [
			'hours' => Iso8601Duration::SUFFIX_HOURS,
			'minutes' => Iso8601Duration::SUFFIX_MINUTES,
			'seconds' => Iso8601Duration::SUFFIX_SECONDS
		]
	];
	/** @internal */
	const PROPERTIES_KEY_DATE = 0;
	/** @internal */
	const PROPERTIES_KEY_TIME = 1;

	/** @var int the number of years */
	private $years = 0;
	/** @var int the number of months */
	private $months = 0;
	/** @var int the number of weeks */
	private $weeks = 0;
	/** @var int the number of days */
	private $days = 0;
	/** @var int the number of hours */
	private $hours = 0;
	/** @var int the number of minutes */
	private $minutes = 0;
	/** @var int the number of seconds */
	private $seconds = 0;
	/** @var int the sign (either `-1` or `1`) for all components of this duration */
	private $sign = 1;

	/**
	 * Returns the number of years
	 *
	 * @return int
	 */
	public function getYears() {
		return $this->years;
	}

	/**
	 * Returns the number of months
	 *
	 * @return int
	 */
	public function getMonths() {
		return $this->months;
	}

	/**
	 * Returns the number of weeks
	 *
	 * @return int
	 */
	public function getWeeks() {
		return $this->weeks;
	}

	/**
	 * Returns the number of days
	 *
	 * @return int
	 */
	public function getDays() {
		return $this->days;
	}

	/**
	 * Returns the number of hours
	 *
	 * @return int
	 */
	public function getHours() {
		return $this->hours;
	}

	/**
	 * Returns the number of minutes
	 *
	 * @return int
	 */
	public function getMinutes() {
		return $this->minutes;
	}

	/**
	 * Returns the number of seconds
	 *
	 * @return int
	 */
	public function getSeconds() {
		return $this->seconds;
	}

	/**
	 * Returns whether the duration is positive
	 *
	 * @return bool
	 */
	public function isPositive() {
		return $this->sign === 1;
	}

	/**
	 * Returns whether the duration is negative
	 *
	 * @return bool
	 */
	public function isNegative() {
		return !$this->isPositive();
	}

	/**
	 * Returns the sign for all components of this duration
	 *
	 * @return int either `-1` or `1`
	 */
	public function getSign() {
		return $this->sign;
	}

	/**
	 * Returns the duration as per ISO 8601
	 *
	 * @return string
	 */
	public function toIso8601() {
		if ($this->isEmpty()) {
			return Iso8601Duration::PREFIX . Iso8601Duration::PREFIX_TIME . 0 . Iso8601Duration::SUFFIX_SECONDS;
		}

		$str = $this->sign == -1 ? '-' : '';

		$str .= Iso8601Duration::PREFIX;

		foreach (self::PROPERTIES as $group => $properties) {
			if ($group === self::PROPERTIES_KEY_TIME) {
				if ($this->hasTime()) {
					$str .= Iso8601Duration::PREFIX_TIME;
				}
			}

			foreach ($properties as $property => $suffix) {
				if (!empty($this->$property)) {
					$str .= $this->$property;
					$str .= $suffix;
				}
			}
		}

		return $str;
	}

	/**
	 * Returns the duration as a number of (average) years
	 *
	 * @return float
	 */
	public function toAverageYears() {
		return $this->toAverageSeconds() / 31556952;
	}

	/**
	 * Returns the duration as a number of (average) months
	 *
	 * @return float
	 */
	public function toAverageMonths() {
		return $this->toAverageSeconds() / 2629746;
	}

	/**
	 * Returns the duration as a number of (average) weeks
	 *
	 * @return float
	 */
	public function toAverageWeeks() {
		return $this->toAverageSeconds() / 604800;
	}

	/**
	 * Returns the duration as a number of (average) days
	 *
	 * @return float
	 */
	public function toAverageDays() {
		return $this->toAverageSeconds() / 86400;
	}

	/**
	 * Returns the duration as a number of (average) hours
	 *
	 * @return float
	 */
	public function toAverageHours() {
		return $this->toAverageSeconds() / 3600;
	}

	/**
	 * Returns the duration as a number of (average) minutes
	 *
	 * @return float
	 */
	public function toAverageMinutes() {
		return $this->toAverageSeconds() / 60;
	}

	/**
	 * Returns the duration as a number of (average) seconds
	 *
	 * @return float
	 */
	public function toAverageSeconds() {
		$seconds = 0;

		$seconds += $this->years * 31556952;
		$seconds += $this->months * 2629746;
		$seconds += $this->weeks * 604800;
		$seconds += $this->days * 86400;
		$seconds += $this->hours * 3600;
		$seconds += $this->minutes * 60;
		$seconds += $this->seconds;
		$seconds *= $this->sign;

		return $seconds;
	}

	/**
	 * Adds another duration to this duration
	 *
	 * @param self $other the duration to add
	 * @return self a new instance
	 * @throws Error (do *not* catch)
	 */
	public function plus(self $other) {
		if (($this->hasWeeks() && $other->hasDateTime()) || ($this->hasDateTime() && $other->hasWeeks())) {
			throw new IllegalDurationOperationError();
		}

		$copy = $this->copy();

		foreach (self::PROPERTIES as $properties) {
			foreach (\array_keys($properties) as $property) {
				$copy->$property += $other->$property;
			}
		}

		return $copy;
	}

	/**
	 * Multiplies the duration by the specified factor
	 *
	 * @param int $factor the positive factor to multiply by
	 * @return self a new instance
	 * @throws Error (do *not* catch)
	 */
	public function multipliedBy($factor) {
		$factor = (int) $factor;

		if ($factor < 0) {
			throw new IllegalDurationOperationError;
		}

		$copy = $this->copy();

		foreach (self::PROPERTIES as $properties) {
			foreach (\array_keys($properties) as $property) {
				$copy->$property *= $factor;
			}
		}

		return $copy;
	}

	/**
	 * Inverts the sign of the duration
	 *
	 * @return self a new instance
	 */
	public function invert() {
		$copy = $this->copy();

		$copy->sign *= -1;

		return $copy;
	}

	/**
	 * Returns whether the duration specifies a number of weeks
	 *
	 * @return bool
	 */
	public function hasWeeks() {
		return $this->weeks !== 0;
	}

	/**
	 * Returns whether the duration specifies date and/or time
	 *
	 * @return bool
	 */
	public function hasDateTime() {
		return $this->hasDate() || $this->hasTime();
	}

	/**
	 * Returns whether the duration specifies a date
	 *
	 * @return bool
	 */
	public function hasDate() {
		return $this->years !== 0 || $this->months !== 0 || $this->days !== 0;
	}

	/**
	 * Returns whether the duration specifies time
	 *
	 * @return bool
	 */
	public function hasTime() {
		return $this->hours !== 0 || $this->minutes !== 0 || $this->seconds !== 0;
	}

	/**
	 * Returns whether the duration is empty
	 *
	 * @return bool
	 */
	public function isEmpty() {
		return !$this->hasDateTime() && !$this->hasWeeks();
	}

	/**
	 * Creates a copy of this instance
	 *
	 * @return self a new instance
	 */
	public function copy() {
		return clone $this;
	}

	public function __toString() {
		return $this->toIso8601();
	}

	/**
	 * Creates a new instance from date and/or time
	 *
	 * @param int|null $years (optional) a positive number of years
	 * @param int|null $months (optional) a positive number of months
	 * @param int|null $days (optional) a positive number of days
	 * @param int|null $hours (optional) a positive number of hours
	 * @param int|null $minutes (optional) a positive number of minutes
	 * @param int|null $seconds (optional) a positive number of seconds
	 * @param int|null $sign (optional) the sign (either `-1` or `1`) for all components of this duration
	 * @return self the new instance
	 */
	public static function fromDateTime($years = null, $months = null, $days = null, $hours = null, $minutes = null, $seconds = null, $sign = null) {
		$instance = new self();

		$instance->setProperty('years', $years);
		$instance->setProperty('months', $months);
		$instance->setProperty('days', $days);
		$instance->setProperty('hours', $hours);
		$instance->setProperty('minutes', $minutes);
		$instance->setProperty('seconds', $seconds);
		$instance->setProperty('sign', $sign == -1 ? -1 : 1, true);

		return $instance;
	}

	/**
	 * Creates a new instance from a number of weeks
	 *
	 * @param int $weeks a positive number of weeks
	 * @param int|null $sign (optional) the sign (either `-1` or `1`) of this duration
	 * @return self the new instance
	 */
	public static function fromWeeks($weeks, $sign = null) {
		$instance = new self();

		$instance->setProperty('weeks', $weeks);
		$instance->setProperty('sign', $sign == -1 ? -1 : 1, true);

		return $instance;
	}

	/**
	 * Creates a new instance from a duration as per ISO 8601
	 *
	 * @param string $duration the duration as per ISO 8601
	 * @return self the new instance
	 * @throws Error (do *not* catch)
	 */
	public static function fromIso8601($duration) {
		if (\preg_match('/^' . Iso8601Duration::REGEX . '$/', $duration, $matches)) {
			$sign = !empty($matches[1]) && $matches[1] === '-' ? -1 : 1;
			$weeks = !empty($matches[2]) ? (int) $matches[2] : 0;
			$dateTime = [
				!empty($matches[3]) ? (int) $matches[3] : 0,
				!empty($matches[4]) ? (int) $matches[4] : 0,
				!empty($matches[5]) ? (int) $matches[5] : 0,
				!empty($matches[6]) ? (int) $matches[6] : 0,
				!empty($matches[7]) ? (int) $matches[7] : 0,
				!empty($matches[8]) ? (int) $matches[8] : 0,
			];

			if ($weeks !== 0) {
				if (!empty(\array_filter($dateTime))) {
					throw new InvalidDurationFormatError();
				}

				return self::fromWeeks($weeks, $sign);
			}
			else {
				return self::fromDateTime(
					$dateTime[0],
					$dateTime[1],
					$dateTime[2],
					$dateTime[3],
					$dateTime[4],
					$dateTime[5],
					$sign
				);
			}
		}
		else {
			throw new InvalidDurationFormatError();
		}
	}

	/**
	 * Sets the specified property to the given value
	 *
	 * @param string $name the name of the property
	 * @param int $value the value to set
	 * @param bool|null $allowNegative (optional) whether to allow negative values
	 * @throws Error (do *not* catch)
	 */
	private function setProperty($name, $value, $allowNegative = null) {
		if (empty($value)) {
			return;
		}

		$name = (string) $name;
		$value = (int) $value;

		if ($allowNegative !== true && $value < 0) {
			throw new InvalidDurationComponentError();
		}

		$this->$name = $value;
	}

	/** Do not allow direct instantiation */
	private function __construct() {}

}
