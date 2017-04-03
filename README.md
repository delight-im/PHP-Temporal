# PHP-Temporal

Immutable date and time for PHP with a convenient interface

## Requirements

 * PHP 5.6.0+

## Installation

 1. Include the library via [Composer](https://getcomposer.org/):

    ```
    $ composer require delight-im/temporal
    ```

 1. Include the Composer autoloader:

    ```php
    require __DIR__ . '/vendor/autoload.php';
    ```

 1. Make sure to set your default time zone in PHP, e.g.:

    ```php
    \date_default_timezone_set('America/Los_Angeles');
    ```

## Usage

 * [Creating instances and parsing dates and time](#creating-instances-and-parsing-dates-and-time)
 * [Adding and subtracting date and time](#adding-and-subtracting-date-and-time)
 * [Fetching individual components or units of date and time](#fetching-individual-components-or-units-of-date-and-time)
 * [Modifying individual components or units of date and time](#modifying-individual-components-or-units-of-date-and-time)
 * [Skipping to boundaries of date and time units](#skipping-to-boundaries-of-date-and-time-units)
 * [Formatting and outputting date and time](#formatting-and-outputting-date-and-time)
 * [Fetching decades, centuries and millennia from dates](#fetching-decades-centuries-and-millennia-from-dates)
 * [Checking for daylight saving time (DST)](#checking-for-daylight-saving-time-dst)
 * [Checking for leap years](#checking-for-leap-years)
 * [Checking for special dates](#checking-for-special-dates)
 * [Calculating differences between instances of date and time](#calculating-differences-between-instances-of-date-and-time)
 * [Reading and writing durations of date and time](#reading-and-writing-durations-of-date-and-time)
 * [Comparing instances of date and time with each other](#comparing-instances-of-date-and-time-with-each-other)
   * [Equality](#equality)
   * [Less than](#less-than)
   * [Greater than](#greater-than)
   * [Less than or equal to](#less-than-or-equal-to)
   * [Greater than or equal to](#greater-than-or-equal-to)
 * [Checking whether dates and times are in the past or future](#checking-whether-dates-and-times-are-in-the-past-or-future)
 * [Changing and converting timezones](#changing-and-converting-timezones)

### Creating instances and parsing dates and time

```php
$dateTime = \Delight\Temporal\Temporal::now();
// or
$dateTime = \Delight\Temporal\Temporal::now('Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::fromUnixSeconds(-14182916);
// or
$dateTime = \Delight\Temporal\Temporal::fromUnixSeconds(-14182916, 'America/Los_Angeles');

// or

$dateTime = \Delight\Temporal\Temporal::fromUnixMillis(-14182916000);
// or
$dateTime = \Delight\Temporal\Temporal::fromUnixMillis(-14182916000, 'Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::fromIso8601DateTimeExtended('1969-07-20T20:18:04+00:00');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601DateTimeExtended('1969-07-20T20:18:04Z', 'America/Los_Angeles');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601DateTimeBasic('19690720T201804Z');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601DateTimeBasic('19690720T201804+0000', 'Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::fromIso8601DateExtended('1969-07-20');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601DateExtended('1969-07-20', 'America/Los_Angeles');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601DateBasic('19690720');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601DateBasic('19690720', 'Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::fromIso8601TimeExtended('20:18:04Z');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601TimeExtended('20:18:04+00:00', 'America/Los_Angeles');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601TimeBasic('201804+0000');
// or
$dateTime = \Delight\Temporal\Temporal::fromIso8601TimeBasic('201804Z', 'Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::fromDateTime(1969, 7, 20, 20, 18, 4);
// or
$dateTime = \Delight\Temporal\Temporal::fromDateTime(1969, 7, 20, 20, 18, 4, 'America/Los_Angeles');

// or

$dateTime = \Delight\Temporal\Temporal::fromDate(1969, 7, 20);
// or
$dateTime = \Delight\Temporal\Temporal::fromDate(1969, 7, 20, 'Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::fromTime(20, 18, 4);
// or
$dateTime = \Delight\Temporal\Temporal::fromTime(20, 18, 4, 'America/Los_Angeles');

// or

$dateTime = \Delight\Temporal\Temporal::fromFormat('20.07.1969', 'd.m.Y');
// or
$dateTime = \Delight\Temporal\Temporal::fromFormat('20.07.1969', 'd.m.Y', 'Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::yesterday();
// or
$dateTime = \Delight\Temporal\Temporal::yesterday('America/Los_Angeles');

// or

$dateTime = \Delight\Temporal\Temporal::today();
// or
$dateTime = \Delight\Temporal\Temporal::today('Asia/Tokyo');

// or

$dateTime = \Delight\Temporal\Temporal::tomorrow();
// or
$dateTime = \Delight\Temporal\Temporal::tomorrow('America/Los_Angeles');
```

### Adding and subtracting date and time

```php
$dateTime = $dateTime->plusYears(7);
// or
$dateTime = $dateTime->minusYears(4);

// or

$dateTime = $dateTime->plusMonths(30);
// or
$dateTime = $dateTime->minusMonths(8);

// or

$dateTime = $dateTime->plusWeeks(6);
// or
$dateTime = $dateTime->minusWeeks(2);

// or

$dateTime = $dateTime->plusDays(28);
// or
$dateTime = $dateTime->minusDays(100);

// or

$dateTime = $dateTime->plusHours(36);
// or
$dateTime = $dateTime->minusHours(19);

// or

$dateTime = $dateTime->plusMinutes(90);
// or
$dateTime = $dateTime->minusMinutes(47);

// or

$dateTime = $dateTime->plusSeconds(30);
// or
$dateTime = $dateTime->minusSeconds(125);

// or

$dateTime = $dateTime->plusDuration('P3Y6M4DT12H30M5S');
// or
$dateTime = $dateTime->minusDuration('P3Y6M4DT12H30M5S');

// or

$dateTime = $dateTime->plusMonths(6)->plusWeeks(2)->minusDays(3);
```

### Fetching individual components or units of date and time

```php
$dateTime->getYear();
// or
$dateTime->getMonth();
// or
$dateTime->getDay();
// or
$dateTime->getHour();
// or
$dateTime->getMinute();
// or
$dateTime->getSecond();

// or

$dateTime->getWeekday();

// or

$dateTime->isMonday();
// or
$dateTime->isTuesday();
// or
$dateTime->isWednesday();
// or
$dateTime->isThursday();
// or
$dateTime->isFriday();
// or
$dateTime->isSaturday();
// or
$dateTime->isSunday();

// or

$dateTime->getWeekOfYear();
// or
$dateTime->getWeekYear();
// or
$dateTime->getDayOfYear();
```

### Modifying individual components or units of date and time

```php
$dateTime = $dateTime->withYear(1969);
// or
$dateTime = $dateTime->withMonth(7);
// or
$dateTime = $dateTime->withDay(20);
// or
$dateTime = $dateTime->withHour(20);
// or
$dateTime = $dateTime->withMinute(18);
// or
$dateTime = $dateTime->withSecond(4);

// or

$dateTime = $dateTime->withDate(1969, 7, 20);
// or
$dateTime = $dateTime->withTime(20, 18, 4);

// or

$dateTime = $dateTime->withDate(1969, 7, 20)->withMinute(0)->withSecond(0);
```

### Skipping to boundaries of date and time units

```php
$dateTime = $dateTime->startOfMinute();
// or
$dateTime = $dateTime->endOfMinute();

// or

$dateTime = $dateTime->startOfHour();
// or
$dateTime = $dateTime->endOfHour();

// or

$dateTime = $dateTime->startOfDay();
// or
$dateTime = $dateTime->endOfDay();

// or

$dateTime = $dateTime->startOfWeek();
// or
$dateTime = $dateTime->endOfWeek();

// or

$dateTime = $dateTime->startOfMonth();
// or
$dateTime = $dateTime->endOfMonth();

// or

$dateTime = $dateTime->startOfYear();
// or
$dateTime = $dateTime->endOfYear();

// or

$dateTime = $dateTime->startOfDecade();
// or
$dateTime = $dateTime->endOfDecade();

// or

$dateTime = $dateTime->startOfCentury();
// or
$dateTime = $dateTime->endOfCentury();

// or

$dateTime = $dateTime->startOfMillennium();
// or
$dateTime = $dateTime->endOfMillennium();

// or

$dateTime = $dateTime->startOfMonth()->endOfWeek()->startOfDay();
```

### Formatting and outputting date and time

```php
$dateTime->toIso8601DateTimeExtended();
// or
$dateTime->toIso8601DateTimeBasic();

// or

$dateTime->toIso8601DateExtended();
// or
$dateTime->toIso8601DateBasic();

// or

$dateTime->toIso8601TimeExtended();
// or
$dateTime->toIso8601TimeBasic();

// or

$dateTime->toUnixSeconds();
// or
$dateTime->toUnixMillis();

// or

$dateTime->toFormat('d.m.Y');
// or
$dateTime->toFormat('l jS \of F Y h:i:s A');
```

### Fetching decades, centuries and millennia from dates

```php
$dateTime->getDecadeOrdinal();
// or
$dateTime->getDecadeNominal();
// or
$dateTime->getYearInDecade();

// or

$dateTime->getCenturyOrdinal();
// or
$dateTime->getCenturyNominal();
// or
$dateTime->getYearInCentury();

// or

$dateTime->getMillenniumOrdinal();
// or
$dateTime->getMillenniumNominal();
// or
$dateTime->getYearInMillennium();
```

### Checking for daylight saving time (DST)

```php
$dateTime->isDst();
```

### Checking for leap years

```php
$dateTime->isLeapYear();
```

### Checking for special dates

```php
$dateTime->isToday();
// or
$dateTime->isYesterday();
// or
$dateTime->isTomorrow();

// or

$dateTime->isAnniversary();

// or

$dateTime->isThisWeek();
// or
$dateTime->isLastWeek();
// or
$dateTime->isNextWeek();

// or

$dateTime->isThisMonth();
// or
$dateTime->isLastMonth();
// or
$dateTime->isNextMonth();

// or

$dateTime->isThisYear();
// or
$dateTime->isLastYear();
// or
$dateTime->isNextYear();

// or

$dateTime->isThisDecade();
// or
$dateTime->isLastDecade();
// or
$dateTime->isNextDecade();

// or

$dateTime->isThisCentury();
// or
$dateTime->isLastCentury();
// or
$dateTime->isNextCentury();

// or

$dateTime->isThisMillennium();
// or
$dateTime->isLastMillennium();
// or
$dateTime->isNextMillennium();
```

### Calculating differences between instances of date and time

```php
$dateTime->calculateMillisUntil($otherDateTime);
// or
$dateTime->calculateSecondsUntil($otherDateTime);
// or
$dateTime->calculateMinutesUntil($otherDateTime);

// or

\Delight\Temporal\Duration $duration = $dateTime->calculateDurationUntil($otherDateTime);
```

### Reading and writing durations of date and time

```php
$duration->getYears();
// or
$duration->getMonths();
// or
$duration->getWeeks();
// or
$duration->getDays();

// or

$duration->getHours();
// or
$duration->getMinutes();
// or
$duration->getSeconds();

// or

$duration->isPositive();
// or
$duration->isNegative();
// or
$duration->getSign();

// or

$duration->toIso8601();

// or

$duration = \Delight\Temporal\Duration::fromDateTime(2, 7, 15, 20, 45, 0);
// or
$duration = \Delight\Temporal\Duration::fromDateTime(4, 0, 7);
// or
$duration = \Delight\Temporal\Duration::fromDateTime(3, 5, 26, 0, 0, 0, -1);

// or

$duration = \Delight\Temporal\Duration::fromWeeks(42);
// or
$duration = \Delight\Temporal\Duration::fromWeeks(8, -1);

// or

$duration = \Delight\Temporal\Duration::fromIso8601('P1M');
// or
$duration = \Delight\Temporal\Duration::fromIso8601('P1Y14D');
// or
$duration = \Delight\Temporal\Duration::fromIso8601('P1MT12H');
// or
$duration = \Delight\Temporal\Duration::fromIso8601('P3Y6M4DT12H30M5S');

// or

$duration = $duration->plus($otherDuration);
// or
$duration = $duration->multipliedBy(4);
// or
$duration = $duration->invert();

// or

$duration = $duration->plus($otherDuration->invert())->multipliedBy(2);
```

### Comparing instances of date and time with each other

#### Equality

```php
$dateTime->isSame($otherDateTime);
// or
$dateTime->isMinuteSame($otherDateTime);
// or
$dateTime->isHourSame($otherDateTime);
// or
$dateTime->isDaySame($otherDateTime);
// or
$dateTime->isMonthSame($otherDateTime);
// or
$dateTime->isYearSame($otherDateTime);
// or
$dateTime->isDecadeSame($otherDateTime);
// or
$dateTime->isCenturySame($otherDateTime);
// or
$dateTime->isMillenniumSame($otherDateTime);
```

#### Less than

```php
$dateTime->isBefore($otherDateTime);
// or
$dateTime->isMinuteBefore($otherDateTime);
// or
$dateTime->isHourBefore($otherDateTime);
// or
$dateTime->isDayBefore($otherDateTime);
// or
$dateTime->isMonthBefore($otherDateTime);
// or
$dateTime->isYearBefore($otherDateTime);
// or
$dateTime->isDecadeBefore($otherDateTime);
// or
$dateTime->isCenturyBefore($otherDateTime);
// or
$dateTime->isMillenniumBefore($otherDateTime);
```

#### Greater than

```php
$dateTime->isAfter($otherDateTime);
// or
$dateTime->isMinuteAfter($otherDateTime);
// or
$dateTime->isHourAfter($otherDateTime);
// or
$dateTime->isDayAfter($otherDateTime);
// or
$dateTime->isMonthAfter($otherDateTime);
// or
$dateTime->isYearAfter($otherDateTime);
// or
$dateTime->isDecadeAfter($otherDateTime);
// or
$dateTime->isCenturyAfter($otherDateTime);
// or
$dateTime->isMillenniumAfter($otherDateTime);
```

#### Less than or equal to

```php
$dateTime->isBeforeOrSame($otherDateTime);
// or
$dateTime->isMinuteBeforeOrSame($otherDateTime);
// or
$dateTime->isHourBeforeOrSame($otherDateTime);
// or
$dateTime->isDayBeforeOrSame($otherDateTime);
// or
$dateTime->isMonthBeforeOrSame($otherDateTime);
// or
$dateTime->isYearBeforeOrSame($otherDateTime);
// or
$dateTime->isDecadeBeforeOrSame($otherDateTime);
// or
$dateTime->isCenturyBeforeOrSame($otherDateTime);
// or
$dateTime->isMillenniumBeforeOrSame($otherDateTime);
```

#### Greater than or equal to

```php
$dateTime->isAfterOrSame($otherDateTime);
// or
$dateTime->isMinuteAfterOrSame($otherDateTime);
// or
$dateTime->isHourAfterOrSame($otherDateTime);
// or
$dateTime->isDayAfterOrSame($otherDateTime);
// or
$dateTime->isMonthAfterOrSame($otherDateTime);
// or
$dateTime->isYearAfterOrSame($otherDateTime);
// or
$dateTime->isDecadeAfterOrSame($otherDateTime);
// or
$dateTime->isCenturyAfterOrSame($otherDateTime);
// or
$dateTime->isMillenniumAfterOrSame($otherDateTime);
```

### Checking whether dates and times are in the past or future

```php
$dateTime->isPast();
// or
$dateTime->isPastMinute();
// or
$dateTime->isPastHour();
// or
$dateTime->isPastDay();
// or
$dateTime->isPastMonth();
// or
$dateTime->isPastYear();
// or
$dateTime->isPastDecade();
// or
$dateTime->isPastCentury();
// or
$dateTime->isPastMillennium();

// or

$dateTime->isFuture();
// or
$dateTime->isFutureMinute();
// or
$dateTime->isFutureHour();
// or
$dateTime->isFutureDay();
// or
$dateTime->isFutureMonth();
// or
$dateTime->isFutureYear();
// or
$dateTime->isFutureDecade();
// or
$dateTime->isFutureCentury();
// or
$dateTime->isFutureMillennium();
```

### Changing and converting timezones

```php
$dateTime = $dateTime->withTimeZone('UTC');
// or
$dateTime = $dateTime->withTimeZone('America/Los_Angeles');
// or
$dateTime = $dateTime->withTimeZone('Asia/Tokyo');

// or

$dateTime = $dateTime->withDefaultTimeZone();
```

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
