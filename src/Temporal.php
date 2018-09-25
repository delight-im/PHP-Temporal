<?php

/*
 * PHP-Temporal (https://github.com/delight-im/PHP-Temporal)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Temporal;

use Delight\Temporal\Iso8601\Iso8601Date;
use Delight\Temporal\Iso8601\Iso8601DateTime;
use Delight\Temporal\Iso8601\Iso8601Duration;
use Delight\Temporal\Iso8601\Iso8601Time;
use Delight\Temporal\Iso8601\Iso8601Weekday;
use Delight\Temporal\Throwable\InvalidDateTimeFormatError;
use Delight\Temporal\Throwable\InvalidTimeZoneIdentifierError;
use Delight\Temporal\Timestamp\Unix;

/** Immutable representation of date and/or time */
final class Temporal {

	/** @var \DateTime the underlying representation of date and time */
	private $dateTime = null;
	/** @var self|null the current mock time that may be set and used for debugging and testing */
	private static $mockNow = null;

	/**
	 * Returns an instance with the specified number of years added
	 *
	 * @param int $years the number of years to add
	 * @return self a new instance
	 */
	public function plusYears($years) {
		return $this->plusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $years . Iso8601Duration::SUFFIX_YEARS
		);
	}

	/**
	 * Returns an instance with the specified number of months added
	 *
	 * @param int $months the number of months to add
	 * @return self a new instance
	 */
	public function plusMonths($months) {
		return $this->plusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $months . Iso8601Duration::SUFFIX_MONTHS
		);
	}

	/**
	 * Returns an instance with the specified number of weeks added
	 *
	 * @param int $weeks the number of weeks to add
	 * @return self a new instance
	 */
	public function plusWeeks($weeks) {
		return $this->plusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $weeks . Iso8601Duration::SUFFIX_WEEKS
		);
	}

	/**
	 * Returns an instance with the specified number of days added
	 *
	 * @param int $days the number of days to add
	 * @return self a new instance
	 */
	public function plusDays($days) {
		return $this->plusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $days . Iso8601Duration::SUFFIX_DAYS
		);
	}

	/**
	 * Returns an instance with the specified number of hours added
	 *
	 * @param int $hours the number of hours to add
	 * @return self a new instance
	 */
	public function plusHours($hours) {
		return $this->plusIso8601Duration(
			Iso8601Duration::PREFIX . Iso8601Duration::PREFIX_TIME . (int) $hours . Iso8601Duration::SUFFIX_HOURS
		);
	}

	/**
	 * Returns an instance with the specified number of minutes added
	 *
	 * @param int $minutes the number of minutes to add
	 * @return self a new instance
	 */
	public function plusMinutes($minutes) {
		return $this->plusIso8601Duration(
			Iso8601Duration::PREFIX . Iso8601Duration::PREFIX_TIME . (int) $minutes . Iso8601Duration::SUFFIX_MINUTES
		);
	}

	/**
	 * Returns an instance with the specified number of seconds added
	 *
	 * @param int $seconds the number of seconds to add
	 * @return self a new instance
	 */
	public function plusSeconds($seconds) {
		return $this->plusIso8601Duration(
			Iso8601Duration::PREFIX . Iso8601Duration::PREFIX_TIME . (int) $seconds . Iso8601Duration::SUFFIX_SECONDS
		);
	}

	/**
	 * Returns an instance with the specified duration added
	 *
	 * Alias of {@see plusIso8601Duration}
	 *
	 * @param string $duration the duration as per ISO 8601 to add
	 * @return self a new instance
	 */
	public function plusDuration($duration) {
		return $this->plusIso8601Duration($duration);
	}

	/**
	 * Returns an instance with the specified duration as per ISO 8601 added
	 *
	 * @param string $iso8601Duration the duration as per ISO 8601 to add
	 * @return self a new instance
	 */
	public function plusIso8601Duration($iso8601Duration) {
		return $this->withIso8601DurationApplied($iso8601Duration, 'add');
	}

	/**
	 * Returns an instance with the specified number of years subtracted
	 *
	 * @param int $years the number of years to subtract
	 * @return self a new instance
	 */
	public function minusYears($years) {
		return $this->minusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $years . Iso8601Duration::SUFFIX_YEARS
		);
	}

	/**
	 * Returns an instance with the specified number of months subtracted
	 *
	 * @param int $months the number of months to subtract
	 * @return self a new instance
	 */
	public function minusMonths($months) {
		return $this->minusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $months . Iso8601Duration::SUFFIX_MONTHS
		);
	}

	/**
	 * Returns an instance with the specified number of weeks subtracted
	 *
	 * @param int $weeks the number of weeks to subtract
	 * @return self a new instance
	 */
	public function minusWeeks($weeks) {
		return $this->minusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $weeks . Iso8601Duration::SUFFIX_WEEKS
		);
	}

	/**
	 * Returns an instance with the specified number of days subtracted
	 *
	 * @param int $days the number of days to subtract
	 * @return self a new instance
	 */
	public function minusDays($days) {
		return $this->minusIso8601Duration(
			Iso8601Duration::PREFIX . (int) $days . Iso8601Duration::SUFFIX_DAYS
		);
	}

	/**
	 * Returns an instance with the specified number of hours subtracted
	 *
	 * @param int $hours the number of hours to subtract
	 * @return self a new instance
	 */
	public function minusHours($hours) {
		return $this->minusIso8601Duration(
			Iso8601Duration::PREFIX . Iso8601Duration::PREFIX_TIME . (int) $hours . Iso8601Duration::SUFFIX_HOURS
		);
	}

	/**
	 * Returns an instance with the specified number of minutes subtracted
	 *
	 * @param int $minutes the number of minutes to subtract
	 * @return self a new instance
	 */
	public function minusMinutes($minutes) {
		return $this->minusIso8601Duration(
			Iso8601Duration::PREFIX . Iso8601Duration::PREFIX_TIME . (int) $minutes . Iso8601Duration::SUFFIX_MINUTES
		);
	}

	/**
	 * Returns an instance with the specified number of seconds subtracted
	 *
	 * @param int $seconds the number of seconds to subtract
	 * @return self a new instance
	 */
	public function minusSeconds($seconds) {
		return $this->minusIso8601Duration(
			Iso8601Duration::PREFIX . Iso8601Duration::PREFIX_TIME . (int) $seconds . Iso8601Duration::SUFFIX_SECONDS
		);
	}

	/**
	 * Returns an instance with the specified duration subtracted
	 *
	 * Alias of {@see minusIso8601Duration}
	 *
	 * @param string $duration the duration as per ISO 8601 to subtract
	 * @return self a new instance
	 */
	public function minusDuration($duration) {
		return $this->minusIso8601Duration($duration);
	}

	/**
	 * Returns an instance with the specified duration as per ISO 8601 subtracted
	 *
	 * @param string $iso8601Duration the duration as per ISO 8601 to subtract
	 * @return self a new instance
	 */
	public function minusIso8601Duration($iso8601Duration) {
		return $this->withIso8601DurationApplied($iso8601Duration, 'sub');
	}

	/**
	 * Returns the year
	 *
	 * @return int
	 */
	public function getYear() {
		return (int) $this->dateTime->format('Y');
	}

	/**
	 * Returns the month of the year
	 *
	 * @return int the month of the year (`1` through `12`)
	 */
	public function getMonth() {
		return (int) $this->dateTime->format('n');
	}

	/**
	 * Returns the day of the month
	 *
	 * @return int the day of the month (`1` through `31`)
	 */
	public function getDay() {
		return (int) $this->dateTime->format('j');
	}

	/**
	 * Returns the hour of the day
	 *
	 * @return int the hour of the day (`0` through `23`)
	 */
	public function getHour() {
		return (int) $this->dateTime->format('G');
	}

	/**
	 * Returns the minute of the hour
	 *
	 * @return int the minute of the hour (`0` through `59`)
	 */
	public function getMinute() {
		return (int) $this->dateTime->format('i');
	}

	/**
	 * Returns the second of the minute
	 *
	 * @return int the second of the minute (`0` through `59`)
	 */
	public function getSecond() {
		return (int) $this->dateTime->format('s');
	}

	/**
	 * Returns the day of the week
	 *
	 * @return int the day of the week (`1` for Monday through `7` for Sunday)
	 */
	public function getWeekday() {
		return (int) $this->dateTime->format('N');
	}

	/**
	 * Returns whether the date is a Monday
	 *
	 * @return bool
	 */
	public function isMonday() {
		return $this->getWeekday() === Iso8601Weekday::MONDAY;
	}

	/**
	 * Returns whether the date is a Tuesday
	 *
	 * @return bool
	 */
	public function isTuesday() {
		return $this->getWeekday() === Iso8601Weekday::TUESDAY;
	}

	/**
	 * Returns whether the date is a Wednesday
	 *
	 * @return bool
	 */
	public function isWednesday() {
		return $this->getWeekday() === Iso8601Weekday::WEDNESDAY;
	}

	/**
	 * Returns whether the date is a Thursday
	 *
	 * @return bool
	 */
	public function isThursday() {
		return $this->getWeekday() === Iso8601Weekday::THURSDAY;
	}

	/**
	 * Returns whether the date is a Friday
	 *
	 * @return bool
	 */
	public function isFriday() {
		return $this->getWeekday() === Iso8601Weekday::FRIDAY;
	}

	/**
	 * Returns whether the date is a Saturday
	 *
	 * @return bool
	 */
	public function isSaturday() {
		return $this->getWeekday() === Iso8601Weekday::SATURDAY;
	}

	/**
	 * Returns whether the date is a Sunday
	 *
	 * @return bool
	 */
	public function isSunday() {
		return $this->getWeekday() === Iso8601Weekday::SUNDAY;
	}

	/**
	 * Returns the number of the week in the year
	 *
	 * @return int the number of the week in the year (`1` through `53`)
	 */
	public function getWeekOfYear() {
		return (int) $this->dateTime->format('W');
	}

	/**
	 * Returns the year that belongs to the number of the week
	 *
	 * This number may differ from the actual year number in early January and late December
	 *
	 * @return int
	 */
	public function getWeekYear() {
		return (int) $this->dateTime->format('o');
	}

	/**
	 * Returns the day of the year
	 *
	 * @return int the day of the year (`1` through `366`)
	 */
	public function getDayOfYear() {
		return (int) $this->dateTime->format('z') + 1;
	}

	/**
	 * Returns an instance with the year set as specified
	 *
	 * @param int $year the year to set
	 * @return self a new instance
	 */
	public function withYear($year) {
		return $this->withDate($year, null, null);
	}

	/**
	 * Returns an instance with the month of the year set as specified
	 *
	 * @param int $month the month of the year (`1` through `12`) to set
	 * @return self a new instance
	 */
	public function withMonth($month) {
		return $this->withDate(null, $month, null);
	}

	/**
	 * Returns an instance with the day of the month set as specified
	 *
	 * @param int $day the day of the month (`1` through `31`) to set
	 * @return self a new instance
	 */
	public function withDay($day) {
		return $this->withDate(null, null, $day);
	}

	/**
	 * Returns an instance with the date set as specified
	 *
	 * @param int|null $year (optional) the year to set
	 * @param int|null $month (optional) the month of the year (`1` through `12`) to set
	 * @param int|null $day (optional) the day of the month (`1` through `31`) to set
	 * @return self a new instance
	 */
	public function withDate($year = null, $month = null, $day = null) {
		return new self($this->dateTime->setDate(
			$year !== null ? (int) $year : $this->getYear(),
			$month !== null ? (int) $month : $this->getMonth(),
			$day !== null ? (int) $day : $this->getDay()
		));
	}

	/**
	 * Returns an instance with the hour of the day set as specified
	 *
	 * @param int $hour the hour of the day (`0` through `23`) to set
	 * @return self a new instance
	 */
	public function withHour($hour) {
		return $this->withTime($hour, null, null);
	}

	/**
	 * Returns an instance with the minute of the hour set as specified
	 *
	 * @param int $minute the minute of the hour (`0` through `59`) to set
	 * @return self a new instance
	 */
	public function withMinute($minute) {
		return $this->withTime(null, $minute, null);
	}

	/**
	 * Returns an instance with the second of the minute set as specified
	 *
	 * @param int $second the second of the minute (`0` through `59`) to set
	 * @return self a new instance
	 */
	public function withSecond($second) {
		return $this->withTime(null, null, $second);
	}

	/**
	 * Returns an instance with the time set as specified
	 *
	 * @param int|null $hour (optional) the hour of the day (`0` through `23`) to set
	 * @param int|null $minute (optional) the minute of the hour (`0` through `59`) to set
	 * @param int|null $second (optional) the second of the minute (`0` through `59`) to set
	 * @return self a new instance
	 */
	public function withTime($hour = null, $minute = null, $second = null) {
		return new self($this->dateTime->setTime(
			$hour !== null ? (int) $hour : $this->getHour(),
			$minute !== null ? (int) $minute : $this->getMinute(),
			$second !== null ? (int) $second : $this->getSecond()
		));
	}

	/**
	 * Returns an instance with the time set to the start of its minute
	 *
	 * @return self a new instance
	 */
	public function startOfMinute() {
		return $this->withSecond(0);
	}

	/**
	 * Returns an instance with the time set to the end of its minute
	 *
	 * @return self a new instance
	 */
	public function endOfMinute() {
		return $this->withSecond(59);
	}

	/**
	 * Returns an instance with the time set to the start of its hour
	 *
	 * @return self a new instance
	 */
	public function startOfHour() {
		return $this->withTime(null, 0, 0);
	}

	/**
	 * Returns an instance with the time set to the end of its hour
	 *
	 * @return self a new instance
	 */
	public function endOfHour() {
		return $this->withTime(null, 59, 59);
	}

	/**
	 * Returns an instance with the time set to the start of its day
	 *
	 * @return self a new instance
	 */
	public function startOfDay() {
		return $this->withTime(0, 0, 0);
	}

	/**
	 * Returns an instance with the time set to the end of its day
	 *
	 * @return self a new instance
	 */
	public function endOfDay() {
		return $this->withTime(23, 59, 59);
	}

	/**
	 * Returns an instance with the time set to the start of its week
	 *
	 * @return self a new instance
	 */
	public function startOfWeek() {
		return $this->withModification('monday this week midnight');
	}

	/**
	 * Returns an instance with the time set to the end of its week
	 *
	 * @return self a new instance
	 */
	public function endOfWeek() {
		return $this->withModification('sunday this week T235959');
	}

	/**
	 * Returns an instance with the time set to the start of its month
	 *
	 * @return self a new instance
	 */
	public function startOfMonth() {
		return $this->withModification('first day of this month midnight');
	}

	/**
	 * Returns an instance with the time set to the end of its month
	 *
	 * @return self a new instance
	 */
	public function endOfMonth() {
		return $this->withModification('last day of this month T235959');
	}

	/**
	 * Returns an instance with the time set to the start of its year
	 *
	 * @return self a new instance
	 */
	public function startOfYear() {
		return $this->withModification('1 january midnight');
	}

	/**
	 * Returns an instance with the time set to the end of its year
	 *
	 * @return self a new instance
	 */
	public function endOfYear() {
		return $this->withModification('31 december T235959');
	}

	/**
	 * Returns an instance with the time set to the start of its decade
	 *
	 * @return self a new instance
	 */
	public function startOfDecade() {
		return $this->withModification(
			'1 january midnight -' . ($this->getYearInDecade()) . ' years'
		);
	}

	/**
	 * Returns an instance with the time set to the end of its decade
	 *
	 * @return self a new instance
	 */
	public function endOfDecade() {
		$monthDayTime = '31 december T235959';
		$year = (9 - ($this->getYearInDecade())) . ' years';

		return $this->withModification($monthDayTime . ' ' . $year);
	}

	/**
	 * Returns an instance with the time set to the start of its century
	 *
	 * @return self a new instance
	 */
	public function startOfCentury() {
		return $this->withModification(
			'1 january midnight -' . ($this->getYearInCentury()) . ' years'
		);
	}

	/**
	 * Returns an instance with the time set to the end of its century
	 *
	 * @return self a new instance
	 */
	public function endOfCentury() {
		$monthDayTime = '31 december T235959';
		$year = (99 - ($this->getYearInCentury())) . ' years';

		return $this->withModification($monthDayTime . ' ' . $year);
	}

	/**
	 * Returns an instance with the time set to the start of its millennium
	 *
	 * @return self a new instance
	 */
	public function startOfMillennium() {
		return $this->withModification(
			'1 january midnight -' . ($this->getYearInMillennium()) . ' years'
		);
	}

	/**
	 * Returns an instance with the time set to the end of its millennium
	 *
	 * @return self a new instance
	 */
	public function endOfMillennium() {
		$monthDayTime = '31 december T235959';
		$year = (999 - ($this->getYearInMillennium())) . ' years';

		return $this->withModification($monthDayTime . ' ' . $year);
	}

	/**
	 * Returns the date and time as per ISO 8601
	 *
	 * This uses the extended format of ISO 8601 which includes separators for better human readability
	 *
	 * Alias of {@see toIso8601DateTimeExtended}
	 *
	 * @return string the date and time as per ISO 8601 (e.g. `1969-07-20T20:18:04+00:00`)
	 */
	public function toIso8601DateTime() {
		return $this->toIso8601DateTimeExtended();
	}

	/**
	 * Returns the date and time as per ISO 8601
	 *
	 * This uses the extended format of ISO 8601 which includes separators for better human readability
	 *
	 * @return string the date and time as per ISO 8601 (e.g. `1969-07-20T20:18:04+00:00`)
	 */
	public function toIso8601DateTimeExtended() {
		return $this->dateTime->format(Iso8601DateTime::FORMAT_EXTENDED);
	}

	/**
	 * Returns the date and time as per ISO 8601
	 *
	 * This uses the basic format of ISO 8601 which omits separators for more efficient machine processing
	 *
	 * @return string the date and time as per ISO 8601 (e.g. `19690720T201804+0000`)
	 */
	public function toIso8601DateTimeBasic() {
		return $this->dateTime->format(Iso8601DateTime::FORMAT_BASIC);
	}

	/**
	 * Returns the date as per ISO 8601
	 *
	 * This uses the extended format of ISO 8601 which includes separators for better human readability
	 *
	 * Alias of {@see toIso8601DateExtended}
	 *
	 * @return string the date as per ISO 8601 (e.g. `1969-07-20`)
	 */
	public function toIso8601Date() {
		return $this->toIso8601DateExtended();
	}

	/**
	 * Returns the date as per ISO 8601
	 *
	 * This uses the extended format of ISO 8601 which includes separators for better human readability
	 *
	 * @return string the date as per ISO 8601 (e.g. `1969-07-20`)
	 */
	public function toIso8601DateExtended() {
		return $this->dateTime->format(Iso8601Date::FORMAT_EXTENDED);
	}

	/**
	 * Returns the date as per ISO 8601
	 *
	 * This uses the basic format of ISO 8601 which omits separators for more efficient machine processing
	 *
	 * @return string the date as per ISO 8601 (e.g. `19690720`)
	 */
	public function toIso8601DateBasic() {
		return $this->dateTime->format(Iso8601Date::FORMAT_BASIC);
	}

	/**
	 * Returns the time as per ISO 8601
	 *
	 * This uses the extended format of ISO 8601 which includes separators for better human readability
	 *
	 * Alias of {@see toIso8601TimeExtended}
	 *
	 * @return string the time as per ISO 8601 (e.g. `20:18:04+00:00`)
	 */
	public function toIso8601Time() {
		return $this->toIso8601TimeExtended();
	}

	/**
	 * Returns the time as per ISO 8601
	 *
	 * This uses the extended format of ISO 8601 which includes separators for better human readability
	 *
	 * @return string the time as per ISO 8601 (e.g. `20:18:04+00:00`)
	 */
	public function toIso8601TimeExtended() {
		return $this->dateTime->format(Iso8601Time::FORMAT_EXTENDED);
	}

	/**
	 * Returns the time as per ISO 8601
	 *
	 * This uses the basic format of ISO 8601 which omits separators for more efficient machine processing
	 *
	 * @return string the time as per ISO 8601 (e.g. `201804+0000`)
	 */
	public function toIso8601TimeBasic() {
		return $this->dateTime->format(Iso8601Time::FORMAT_BASIC);
	}

	/**
	 * Returns the UNIX timestamp in seconds
	 *
	 * @return int
	 */
	public function toUnixSeconds() {
		return (int) $this->dateTime->format(Unix::FORMAT_INTEGER);
	}

	/**
	 * Returns the UNIX timestamp in milliseconds
	 *
	 * @return int
	 */
	public function toUnixMillis() {
		$secondsWithFraction = (float) $this->dateTime->format(Unix::FORMAT_FLOAT);

		return (int) \round($secondsWithFraction * 1000);
	}

	/**
	 * Returns the date and/or time as requested in the specified format
	 *
	 * @param string $format the desired format, described using the formatting options of {@see \date}
	 * @return string
	 */
	public function toFormat($format) {
		return $this->dateTime->format($format);
	}

	/**
	 * Returns the date and time as a {@see \DateTime} instance
	 *
	 * @return \DateTime
	 */
	public function toDateTime() {
		return 	\DateTime::createFromFormat(
			Unix::FORMAT_FLOAT,
			$this->dateTime->format(Unix::FORMAT_FLOAT),
			$this->dateTime->getTimezone()
		);
	}

	/**
	 * Returns the date and time as a {@see \DateTimeImmutable} instance
	 *
	 * @return \DateTimeImmutable
	 */
	public function toDateTimeImmutable() {
		return $this->dateTime;
	}

	/**
	 * Returns the ordinal number for the decade
	 *
	 * @return int the ordinal number for the decade (e.g. `197` for year `1969`)
	 */
	public function getDecadeOrdinal() {
		return $this->getDecadeSignificand() + self::signum($this->getYear());
	}

	/**
	 * Returns the nominal number for the decade
	 *
	 * @return int the nominal number for the decade (e.g. `1960` for year `1969`)
	 */
	public function getDecadeNominal() {
		return $this->getDecadeSignificand() * 10;
	}

	/**
	 * Returns the ordinal number for the century
	 *
	 * @return int the ordinal number for the century (e.g. `20` for year `1969`)
	 */
	public function getCenturyOrdinal() {
		return $this->getCenturySignificand() + self::signum($this->getYear());
	}

	/**
	 * Returns the nominal number for the century
	 *
	 * @return int the nominal number for the century (e.g. `1900` for year `1969`)
	 */
	public function getCenturyNominal() {
		return $this->getCenturySignificand() * 100;
	}

	/**
	 * Returns the ordinal number for the millennium
	 *
	 * @return int the ordinal number for the millennium (e.g. `2` for year `1969`)
	 */
	public function getMillenniumOrdinal() {
		return $this->getMillenniumSignificand() + self::signum($this->getYear());
	}

	/**
	 * Returns the nominal number for the millennium
	 *
	 * @return int the nominal number for the millennium (e.g. `1000` for year `1969`)
	 */
	public function getMillenniumNominal() {
		return $this->getMillenniumSignificand() * 1000;
	}

	/**
	 * Returns whether daylight saving time (DST) is in effect
	 *
	 * Alias of {@see isDaylightSavingTime}
	 *
	 * @return bool
	 */
	public function isDst() {
		return $this->isDaylightSavingTime();
	}

	/**
	 * Returns whether daylight saving time (DST) is in effect
	 *
	 * @return bool
	 */
	public function isDaylightSavingTime() {
		return $this->dateTime->format('I') == 1;
	}

	/**
	 * Returns whether the year is a leap year
	 *
	 * @return bool
	 */
	public function isLeapYear() {
		return $this->dateTime->format('L') == 1;
	}

	/**
	 * Returns the number of the year in its decade
	 *
	 * @return int the number of the year in its decade (`0` through `9`)
	 */
	public function getYearInDecade() {
		return $this->getYear() % 10;
	}

	/**
	 * Returns the number of the year in its century
	 *
	 * @return int the number of the year in its century (`0` through `99`)
	 */
	public function getYearInCentury() {
		return $this->getYear() % 100;
	}

	/**
	 * Returns the number of the year in its millennium
	 *
	 * @return int the number of the year in its millennium (`0` through `999`)
	 */
	public function getYearInMillennium() {
		return $this->getYear() % 1000;
	}

	/**
	 * Returns whether the current instance is today
	 *
	 * @return bool
	 */
	public function isToday() {
		return $this->isDaySame(self::now());
	}

	/**
	 * Returns whether the current instance is yesterday
	 *
	 * @return bool
	 */
	public function isYesterday() {
		return $this->isDaySame(self::yesterday());
	}

	/**
	 * Returns whether the current instance is tomorrow
	 *
	 * @return bool
	 */
	public function isTomorrow() {
		return $this->isDaySame(self::tomorrow());
	}

	/**
	 * Returns whether the current instance has the same month and day as today
	 *
	 * @return bool
	 */
	public function isAnniversary() {
		$now = self::now();

		return $this->getMonth() === $now->getMonth() && $this->getDay() === $now->getDay();
	}

	/**
	 * Returns the duration in milliseconds from this instance to the other instance
	 *
	 * @param self $other
	 * @return float
	 */
	public function calculateMillisUntil(self $other) {
		return $this->calculateSecondsUntil($other) * 1000;
	}

	/**
	 * Returns the duration in seconds from this instance to the other instance
	 *
	 * @param self $other
	 * @return float
	 */
	public function calculateSecondsUntil(self $other) {
		return (float) $other->dateTime->format(Unix::FORMAT_FLOAT) - (float) $this->dateTime->format(Unix::FORMAT_FLOAT);
	}

	/**
	 * Returns the duration in minutes from this instance to the other instance
	 *
	 * @param self $other
	 * @return float
	 */
	public function calculateMinutesUntil(self $other) {
		return $this->calculateSecondsUntil($other) / 60;
	}

	/**
	 * Returns the duration from this instance to the other instance
	 *
	 * Alias of {@see calculateIso8601DurationUntil}
	 *
	 * @param self $other
	 * @return Duration
	 */
	public function calculateDurationUntil(self $other) {
		return $this->calculateIso8601DurationUntil($other);
	}

	/**
	 * Returns the duration from this instance to the other instance
	 *
	 * @param self $other
	 * @return Duration
	 */
	public function calculateIso8601DurationUntil(self $other) {
		$components = $this->dateTime->diff($other->dateTime);

		return Duration::fromDateTime(
			$components->y,
			$components->m,
			$components->d,
			$components->h,
			$components->i,
			$components->s,
			$components->invert == 1 ? -1 : 1
		);
	}

	/**
	 * Returns whether the other instance has the same date and time
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isSame(self $other) {
		return self::compareInstances($this, $other, 15) === 0;
	}

	/**
	 * Returns whether the other instance has the same date, hour and minute
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMinuteSame(self $other) {
		return self::compareInstances($this, $other, 13) === 0;
	}

	/**
	 * Returns whether the other instance has the same date and hour
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isHourSame(self $other) {
		return self::compareInstances($this, $other, 11) === 0;
	}

	/**
	 * Returns whether the other instance has the same date
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDaySame(self $other) {
		return self::compareInstances($this, $other, 8) === 0;
	}

	/**
	 * Returns whether the other instance has the same year and month
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMonthSame(self $other) {
		return self::compareInstances($this, $other, 6) === 0;
	}

	/**
	 * Returns whether the other instance has the same year
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isYearSame(self $other) {
		return self::compareInstances($this, $other, 4) === 0;
	}

	/**
	 * Returns whether the other instance has the same decade
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDecadeSame(self $other) {
		return self::compareInstances($this, $other, 3) === 0;
	}

	/**
	 * Returns whether the other instance has the same century
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isCenturySame(self $other) {
		return self::compareInstances($this, $other, 2) === 0;
	}

	/**
	 * Returns whether the other instance has the same millennium
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMillenniumSame(self $other) {
		return self::compareInstances($this, $other, 1) === 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to date and time
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isBefore(self $other) {
		return self::compareInstances($this, $other, 15) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to date, hour and minute
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMinuteBefore(self $other) {
		return self::compareInstances($this, $other, 13) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to date and hour
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isHourBefore(self $other) {
		return self::compareInstances($this, $other, 11) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to the date
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDayBefore(self $other) {
		return self::compareInstances($this, $other, 8) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to year and month
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMonthBefore(self $other) {
		return self::compareInstances($this, $other, 6) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to the year
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isYearBefore(self $other) {
		return self::compareInstances($this, $other, 4) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to the decade
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDecadeBefore(self $other) {
		return self::compareInstances($this, $other, 3) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to the century
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isCenturyBefore(self $other) {
		return self::compareInstances($this, $other, 2) < 0;
	}

	/**
	 * Returns whether the current instance is before the other one with respect to the millennium
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMillenniumBefore(self $other) {
		return self::compareInstances($this, $other, 1) < 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to date and time
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isAfter(self $other) {
		return self::compareInstances($this, $other, 15) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to date, hour and minute
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMinuteAfter(self $other) {
		return self::compareInstances($this, $other, 13) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to date and hour
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isHourAfter(self $other) {
		return self::compareInstances($this, $other, 11) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to the date
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDayAfter(self $other) {
		return self::compareInstances($this, $other, 8) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to year and month
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMonthAfter(self $other) {
		return self::compareInstances($this, $other, 6) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to the year
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isYearAfter(self $other) {
		return self::compareInstances($this, $other, 4) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to the decade
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDecadeAfter(self $other) {
		return self::compareInstances($this, $other, 3) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to the century
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isCenturyAfter(self $other) {
		return self::compareInstances($this, $other, 2) > 0;
	}

	/**
	 * Returns whether the current instance is after the other one with respect to the millennium
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMillenniumAfter(self $other) {
		return self::compareInstances($this, $other, 1) > 0;
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to date and time
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isBeforeOrSame(self $other) {
		return !$this->isAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to date, hour and minute
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMinuteBeforeOrSame(self $other) {
		return !$this->isMinuteAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to date and hour
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isHourBeforeOrSame(self $other) {
		return !$this->isHourAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to the date
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDayBeforeOrSame(self $other) {
		return !$this->isDayAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to year and month
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMonthBeforeOrSame(self $other) {
		return !$this->isMonthAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to the year
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isYearBeforeOrSame(self $other) {
		return !$this->isYearAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to the decade
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDecadeBeforeOrSame(self $other) {
		return !$this->isDecadeAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to the century
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isCenturyBeforeOrSame(self $other) {
		return !$this->isCenturyAfter($other);
	}

	/**
	 * Returns whether the current instance is before or equal to the other one with respect to the millennium
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMillenniumBeforeOrSame(self $other) {
		return !$this->isMillenniumAfter($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to date and time
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isAfterOrSame(self $other) {
		return !$this->isBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to date, hour and minute
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMinuteAfterOrSame(self $other) {
		return !$this->isMinuteBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to date and hour
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isHourAfterOrSame(self $other) {
		return !$this->isHourBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to the date
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDayAfterOrSame(self $other) {
		return !$this->isDayBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to year and month
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMonthAfterOrSame(self $other) {
		return !$this->isMonthBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to the year
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isYearAfterOrSame(self $other) {
		return !$this->isYearBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to the decade
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isDecadeAfterOrSame(self $other) {
		return !$this->isDecadeBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to the century
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isCenturyAfterOrSame(self $other) {
		return !$this->isCenturyBefore($other);
	}

	/**
	 * Returns whether the current instance is after or equal to the other one with respect to the millennium
	 *
	 * @param self $other the other instance to compare with
	 * @return bool
	 */
	public function isMillenniumAfterOrSame(self $other) {
		return !$this->isMillenniumBefore($other);
	}

	/**
	 * Returns whether the current instance is in the past with respect to date and time
	 *
	 * @return bool
	 */
	public function isPast() {
		return $this->isBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to date, hour and minute
	 *
	 * @return bool
	 */
	public function isPastMinute() {
		return $this->isMinuteBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to date and hour
	 *
	 * @return bool
	 */
	public function isPastHour() {
		return $this->isHourBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to the date
	 *
	 * @return bool
	 */
	public function isPastDay() {
		return $this->isDayBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to year and month
	 *
	 * @return bool
	 */
	public function isPastMonth() {
		return $this->isMonthBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to the year
	 *
	 * @return bool
	 */
	public function isPastYear() {
		return $this->isYearBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to the decade
	 *
	 * @return bool
	 */
	public function isPastDecade() {
		return $this->isDecadeBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to the century
	 *
	 * @return bool
	 */
	public function isPastCentury() {
		return $this->isCenturyBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the past with respect to the millennium
	 *
	 * @return bool
	 */
	public function isPastMillennium() {
		return $this->isMillenniumBefore(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to date and time
	 *
	 * @return bool
	 */
	public function isFuture() {
		return $this->isAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to date, hour and minute
	 *
	 * @return bool
	 */
	public function isFutureMinute() {
		return $this->isMinuteAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to date and hour
	 *
	 * @return bool
	 */
	public function isFutureHour() {
		return $this->isHourAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to the date
	 *
	 * @return bool
	 */
	public function isFutureDay() {
		return $this->isDayAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to year and month
	 *
	 * @return bool
	 */
	public function isFutureMonth() {
		return $this->isMonthAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to the year
	 *
	 * @return bool
	 */
	public function isFutureYear() {
		return $this->isYearAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to the decade
	 *
	 * @return bool
	 */
	public function isFutureDecade() {
		return $this->isDecadeAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to the century
	 *
	 * @return bool
	 */
	public function isFutureCentury() {
		return $this->isCenturyAfter(self::now());
	}

	/**
	 * Returns whether the current instance is in the future with respect to the millennium
	 *
	 * @return bool
	 */
	public function isFutureMillennium() {
		return $this->isMillenniumAfter(self::now());
	}

	/**
	 * Returns whether the current instance was last week
	 *
	 * @return bool
	 */
	public function isLastWeek() {
		return $this->getWeekOfYear() === self::now()->minusWeeks(1)->getWeekOfYear();
	}

	/**
	 * Returns whether the current instance is this week
	 *
	 * @return bool
	 */
	public function isThisWeek() {
		return $this->getWeekOfYear() === self::now()->getWeekOfYear();
	}

	/**
	 * Returns whether the current instance will be next week
	 *
	 * @return bool
	 */
	public function isNextWeek() {
		return $this->getWeekOfYear() === self::now()->plusWeeks(1)->getWeekOfYear();
	}

	/**
	 * Returns whether the current instance was last month
	 *
	 * @return bool
	 */
	public function isLastMonth() {
		return $this->getMonth() === self::now()->minusMonths(1)->getMonth();
	}

	/**
	 * Returns whether the current instance is this month
	 *
	 * @return bool
	 */
	public function isThisMonth() {
		return $this->getMonth() === self::now()->getMonth();
	}

	/**
	 * Returns whether the current instance will be next month
	 *
	 * @return bool
	 */
	public function isNextMonth() {
		return $this->getMonth() === self::now()->plusMonths(1)->getMonth();
	}

	/**
	 * Returns whether the current instance was last year
	 *
	 * @return bool
	 */
	public function isLastYear() {
		return $this->getYear() === self::now()->minusYears(1)->getYear();
	}

	/**
	 * Returns whether the current instance is this year
	 *
	 * @return bool
	 */
	public function isThisYear() {
		return $this->getYear() === self::now()->getYear();
	}

	/**
	 * Returns whether the current instance will be next year
	 *
	 * @return bool
	 */
	public function isNextYear() {
		return $this->getYear() === self::now()->plusYears(1)->getYear();
	}

	/**
	 * Returns whether the current instance was last decade
	 *
	 * @return bool
	 */
	public function isLastDecade() {
		return $this->getDecadeSignificand() === self::now()->minusYears(10)->getDecadeSignificand();
	}

	/**
	 * Returns whether the current instance is this decade
	 *
	 * @return bool
	 */
	public function isThisDecade() {
		return $this->getDecadeSignificand() === self::now()->getDecadeSignificand();
	}

	/**
	 * Returns whether the current instance will be next decade
	 *
	 * @return bool
	 */
	public function isNextDecade() {
		return $this->getDecadeSignificand() === self::now()->plusYears(10)->getDecadeSignificand();
	}

	/**
	 * Returns whether the current instance was last century
	 *
	 * @return bool
	 */
	public function isLastCentury() {
		return $this->getCenturySignificand() === self::now()->minusYears(100)->getCenturySignificand();
	}

	/**
	 * Returns whether the current instance is this century
	 *
	 * @return bool
	 */
	public function isThisCentury() {
		return $this->getCenturySignificand() === self::now()->getCenturySignificand();
	}

	/**
	 * Returns whether the current instance will be next century
	 *
	 * @return bool
	 */
	public function isNextCentury() {
		return $this->getCenturySignificand() === self::now()->plusYears(100)->getCenturySignificand();
	}

	/**
	 * Returns whether the current instance was last millennium
	 *
	 * @return bool
	 */
	public function isLastMillennium() {
		return $this->getMillenniumSignificand() === self::now()->minusYears(1000)->getMillenniumSignificand();
	}

	/**
	 * Returns whether the current instance is this millennium
	 *
	 * @return bool
	 */
	public function isThisMillennium() {
		return $this->getMillenniumSignificand() === self::now()->getMillenniumSignificand();
	}

	/**
	 * Returns whether the current instance will be next millennium
	 *
	 * @return bool
	 */
	public function isNextMillennium() {
		return $this->getMillenniumSignificand() === self::now()->plusYears(1000)->getMillenniumSignificand();
	}

	/**
	 * Returns an instance with the time zone changed to that with the specified identifier
	 *
	 * Passing `null` will set the default time zone
	 *
	 * @param string $identifier|null the identifier of the time zone to use (e.g. `Asia/Tokyo`) or `null`
	 * @return self a new instance
	 */
	public function withTimeZone($identifier = null) {
		return new self($this->dateTime->setTimezone(self::makeTimeZone($identifier)));
	}

	/**
	 * Returns an instance with the time zone changed to the default one
	 *
	 * @return self a new instance
	 */
	public function withDefaultTimeZone() {
		return $this->withTimeZone(null);
	}

	/**
	 * Returns a copy of the current instance
	 * @return self a new instance
	 */
	public function copy() {
		return clone $this;
	}

	public function __toString() {
		return $this->toIso8601DateTimeExtended();
	}

	/**
	 * Creates a new instance with the current date and time
	 *
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function now($timeZone = null) {
		if (self::hasMockNow()) {
			return self::getMockNow()->withTimeZone($timeZone);
		}

		return new self(new \DateTime(null, self::makeTimeZone($timeZone)));
	}

	/**
	 * Returns a new instance with the date and time from the specified UNIX timestamp
	 *
	 * @param int|float $unixSeconds the UNIX timestamp in seconds
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromUnixSeconds($unixSeconds, $timeZone = null) {
		return self::createInstanceFromFormat(
			Unix::FORMAT_FLOAT,
			\number_format($unixSeconds, 6, '.', ''),
			$timeZone
		);
	}

	/**
	 * Returns a new instance with the date and time from the specified UNIX timestamp
	 *
	 * @param int $unixMillis the UNIX timestamp in milliseconds
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromUnixMillis($unixMillis, $timeZone = null) {
		return self::fromUnixSeconds($unixMillis / 1000, $timeZone);
	}

	/**
	 * Creates a new instance with the specified date and time as per ISO 8601
	 *
	 * Alias of {@see fromIso8601DateTimeExtended}
	 *
	 * @param string $dateTime the date and time in extended format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601DateTime($dateTime, $timeZone = null) {
		return self::fromIso8601DateTimeExtended($dateTime, $timeZone);
	}

	/**
	 * Creates a new instance with the specified date and time as per ISO 8601
	 *
	 * @param string $dateTime the date and time in extended format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601DateTimeExtended($dateTime, $timeZone = null) {
		return self::createInstanceFromFormat(Iso8601DateTime::FORMAT_EXTENDED, $dateTime, $timeZone);
	}

	/**
	 * Creates a new instance with the specified date and time as per ISO 8601
	 *
	 * @param string $dateTime the date and time in basic format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601DateTimeBasic($dateTime, $timeZone = null) {
		return self::createInstanceFromFormat(Iso8601DateTime::FORMAT_BASIC, $dateTime, $timeZone);
	}

	/**
	 * Creates a new instance with the specified date as per ISO 8601
	 *
	 * Alias of {@see fromIso8601DateExtended}
	 *
	 * @param string $date the date in extended format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601Date($date, $timeZone = null) {
		return self::fromIso8601DateExtended($date, $timeZone);
	}

	/**
	 * Creates a new instance with the specified date as per ISO 8601
	 *
	 * @param string $date the date in extended format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601DateExtended($date, $timeZone = null) {
		return self::createInstanceFromFormat(
			Iso8601Date::FORMAT_EXTENDED,
			$date,
			$timeZone
		);
	}

	/**
	 * Creates a new instance with the specified date as per ISO 8601
	 *
	 * @param string $date the date in basic format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601DateBasic($date, $timeZone = null) {
		return self::createInstanceFromFormat(
			Iso8601Date::FORMAT_BASIC,
			$date,
			$timeZone
		);
	}

	/**
	 * Creates a new instance with the specified time as per ISO 8601
	 *
	 * Alias of {@see fromIso8601TimeExtended}
	 *
	 * @param string $time the time in extended format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601Time($time, $timeZone = null) {
		return self::fromIso8601TimeExtended($time, $timeZone);
	}

	/**
	 * Creates a new instance with the specified time as per ISO 8601
	 *
	 * @param string $time the time in extended format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601TimeExtended($time, $timeZone = null) {
		return self::createInstanceFromFormat(Iso8601Time::FORMAT_EXTENDED, $time, $timeZone);
	}

	/**
	 * Creates a new instance with the specified time as per ISO 8601
	 *
	 * @param string $time the time in basic format as per ISO 8601
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromIso8601TimeBasic($time, $timeZone = null) {
		return self::createInstanceFromFormat(Iso8601Time::FORMAT_BASIC, $time, $timeZone);
	}

	/**
	 * Creates a new instance with the specified date and time
	 *
	 * Any component that is not set will be set to "now"
	 *
	 * @param int|null $year (optional) the year to set
	 * @param int|null $month (optional) the month of the year (`1` through `12`) to set
	 * @param int|null $day (optional) the day of the month (`1` through `31`) to set
	 * @param int|null $hour (optional) the hour of the day (`0` through `23`) to set
	 * @param int|null $minute (optional) the minute of the hour (`0` through `59`) to set
	 * @param int|null $second (optional) the second of the minute (`0` through `59`) to set
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromDateTime($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $timeZone = null) {
		return self::now($timeZone)->withDate($year, $month, $day)->withTime($hour, $minute, $second);
	}

	/**
	 * Creates a new instance with the specified date
	 *
	 * Any component that is not set (and the time) will be set to "now"
	 *
	 * @param int|null $year (optional) the year to set
	 * @param int|null $month (optional) the month of the year (`1` through `12`) to set
	 * @param int|null $day (optional) the day of the month (`1` through `31`) to set
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromDate($year = null, $month = null, $day = null, $timeZone = null) {
		return self::now($timeZone)->withDate($year, $month, $day);
	}

	/**
	 * Creates a new instance with the specified time
	 *
	 * Any component that is not set (and the date) will be set to "now"
	 *
	 * @param int|null $hour (optional) the hour of the day (`0` through `23`) to set
	 * @param int|null $minute (optional) the minute of the hour (`0` through `59`) to set
	 * @param int|null $second (optional) the second of the minute (`0` through `59`) to set
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromTime($hour = null, $minute = null, $second = null, $timeZone = null) {
		return self::now($timeZone)->withTime($hour, $minute, $second);
	}

	/**
	 * Creates a new instance by parsing the supplied input as defined in the specified format
	 *
	 * @param string $dateTime the input to validate and parse as defined in the specified format
	 * @param string $format the format of the input, described using the formatting options of {@see \date}
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function fromFormat($dateTime, $format, $timeZone = null) {
		return self::createInstanceFromFormat($format, $dateTime, $timeZone);
	}

	/**
	 * Creates a new instance from the supplied {@see \DateTimeInterface} instance
	 *
	 * @param \DateTimeInterface $dateTimeInterface the {@see \DateTime} or {@see \DateTimeImmutable} instance
	 * @return self the new instance
	 */
	public static function fromDateTimeInterface(\DateTimeInterface $dateTimeInterface) {
		return new self($dateTimeInterface);
	}

	/**
	 * Creates a new instance with yesterday's date
	 *
	 * The time is set to the current time
	 *
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function yesterday($timeZone = null) {
		return self::now($timeZone)->minusDays(1);
	}

	/**
	 * Creates a new instance with the current date and time
	 *
	 * Alias of {@see now}
	 *
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function today($timeZone = null) {
		return self::now($timeZone);
	}

	/**
	 * Creates a new instance with tomorrow's date
	 *
	 * The time is set to the current time
	 *
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 */
	public static function tomorrow($timeZone = null) {
		return self::now($timeZone)->plusDays(1);
	}

	/**
	 * Returns whether a current mock time has been set for debugging and testing
	 *
	 * @return bool
	 */
	public static function hasMockNow() {
		return self::$mockNow !== null;
	}

	/**
	 * Returns the current mock time (if set) that may be used for debugging and testing
	 *
	 * @return self|null the instance or `null` if not set
	 */
	public static function getMockNow() {
		return self::$mockNow;
	}

	/**
	 * Sets (or unsets) the current mock time that may be used for debugging and testing
	 *
	 * @param self|null $mockNow the instance to set or `null` to unset
	 */
	public static function setMockNow(self $mockNow = null) {
		self::$mockNow = $mockNow;
	}

	/**
	 * Returns the significand of the decade
	 *
	 * @return int
	 */
	private function getDecadeSignificand() {
		return self::intdiv($this->getYear(), 10);
	}

	/**
	 * Returns the significand of the century
	 *
	 * @return int
	 */
	private function getCenturySignificand() {
		return self::intdiv($this->getYear(), 100);
	}

	/**
	 * Returns the significand of the millennium
	 *
	 * @return int
	 */
	private function getMillenniumSignificand() {
		return self::intdiv($this->getYear(), 1000);
	}

	/**
	 * Applies the specified duration as per ISO 8601
	 *
	 * @param string $duration the duration as per ISO 8601
	 * @param string $dateTimeInstanceMethodName the method to call on the {@see \DateTime} instance
	 * @return self a new instance
	 */
	private function withIso8601DurationApplied($duration, $dateTimeInstanceMethodName) {
		$copy = new self($this->dateTime->$dateTimeInstanceMethodName(new \DateInterval($duration)));

		// if the *actual* day does not exist in the *intended* month which causes PHP to calculate *wrongly*
		// fix the calculation retroactively
		if (Iso8601Duration::affectsMonthsOnly($duration) && $copy->getDay() !== $this->getDay()) {
			return $copy->withModification('last day of previous month');
		}

		return $copy;
	}

	/**
	 * Changes the date and/or time as prescribed by the supplied string
	 *
	 * @link http://php.net/manual/en/datetime.formats.php
	 *
	 * @param string $modification the modification as understood by {@see \strtotime} to apply
	 * @return self a new instance
	 */
	private function withModification($modification) {
		return new self($this->dateTime->modify($modification));
	}

	/**
	 * Changes the time zone to that with the specified identifier
	 *
	 * Passing `null` will set the default time zone
	 * @param string|null $identifier the identifier of the time zone to use (e.g. `Asia/Tokyo`) or `null`
	 * @return self the modified instance
	 */
	private function setTimeZone($identifier = null) {
		return $this->setTimeZoneInstance(
			self::makeTimeZone($identifier)
		);
	}

	/**
	 * Changes the time zone to the supplied one
	 *
	 * @param \DateTimeZone $timeZone the time zone to use
	 * @return self the modified instance
	 */
	private function setTimeZoneInstance(\DateTimeZone $timeZone) {
		$this->dateTime->setTimezone($timeZone);

		return $this;
	}

	/** Do not allow direct instantiation */
	private function __construct(\DateTimeInterface $dateTime) {
		if (!$dateTime instanceof \DateTimeImmutable) {
			$dateTime = \DateTimeImmutable::createFromMutable($dateTime);
		}

		$this->dateTime = $dateTime;
	}

	/**
	 * Compares the two supplied instances with respect to the specified precision
	 *
	 * The basic format of ISO 8601 with date and time is used for the comparison
	 *
	 * @see toIso8601DateTimeBasic
	 *
	 * @param self $a the first instance
	 * @param self $b the second instance
	 * @param int $substringPrecision the number of characters to compare (between 1 and 15)
	 * @return int whether the first instance is less than (< 0), equal to (0) or greater than (> 0) the second
	 */
	private static function compareInstances(self $a, self $b, $substringPrecision) {
		$substringPrecision = (int) $substringPrecision;

		return \strcasecmp(
			\substr($a->toIso8601DateTimeBasic(), 0, $substringPrecision),
			\substr($b->toIso8601DateTimeBasic(), 0, $substringPrecision)
		);
	}

	/**
	 * Creates a new instance by parsing the supplied input as defined in the specified format
	 *
	 * @param string $format the format of the input, described using the formatting options of {@see \date}
	 * @param string $dateTime the input to validate and parse as defined in the specified format
	 * @param string|null $timeZone (optional) the identifier of the time zone to use (e.g. `Asia/Tokyo`)
	 * @return self the new instance
	 * @throws InvalidDateTimeFormatError if the date and/or time has been invalid with respect to the format
	 */
	private static function createInstanceFromFormat($format, $dateTime, $timeZone = null) {
		$parsed = \DateTime::createFromFormat($format, $dateTime, self::makeTimeZone($timeZone));
		if ($parsed !== false) {
			return (new self($parsed))->withTimeZone($timeZone);
		}

		throw new InvalidDateTimeFormatError();
	}

	/**
	 * Creates a new instance with the supplied object as the representation of date and/or time
	 * @param \DateTime $dateTime the representation of date and/or time
	 * @return self the new instance
	 */
	private static function createInstance(\DateTime $dateTime) {
		return new self($dateTime);
	}

	/**
	 * Creates an instance for the specified time zone
	 *
	 * Passing `null` will create an instance of the default time zone
	 *
	 * @param string|null $identifier the identifier of the time zone (e.g. `Asia/Tokyo`) or `null`
	 * @return \DateTimeZone|null the new instance
	 * @throws InvalidTimeZoneIdentifierError if the time zone identifier has been invalid
	 */
	private static function makeTimeZone($identifier = null) {
		try {
			return new \DateTimeZone($identifier !== null ? $identifier : \date_default_timezone_get());
		} catch (\Exception $e) {
			throw new InvalidTimeZoneIdentifierError();
		}
	}

	/**
	 * Returns the integer quotient of the two supplied numbers
	 *
	 * @param int $dividend
	 * @param int $divisor
	 * @return int
	 */
	private static function intdiv($dividend, $divisor) {
		return ($dividend - ($dividend % $divisor)) / $divisor;
	}

	/**
	 * Returns the sign of the given number
	 *
	 * @param int $number
	 * @return int the sign (`-1` for negative numbers, `0` for zero and `1` for positive numbers)
	 */
	private static function signum($number) {
		return $number > 0 ? 1 : ($number < 0 ? -1 : 0);
	}

}
