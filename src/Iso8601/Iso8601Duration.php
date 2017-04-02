<?php

/*
 * PHP-Temporal (https://github.com/delight-im/PHP-Temporal)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Temporal\Iso8601;

/** Constants and utilities for working with durations as per ISO 8601 */
final class Iso8601Duration {

	/** @internal */
	const PREFIX = 'P';
	/** @internal */
	const PREFIX_TIME = 'T';
	/** @internal */
	const REGEX = '(-)?P(?:' . self::REGEX_PART_WEEK . '|' . self::REGEX_PART_DATE_TIME . ')';
	/** @internal */
	const REGEX_PART_DATE = '(?:(' . self::REGEX_PART_NUMBER . ')Y)?(?:(' . self::REGEX_PART_NUMBER . ')M)?(?:(' . self::REGEX_PART_NUMBER . ')D)?';
	/** @internal */
	const REGEX_PART_DATE_TIME = self::REGEX_PART_DATE . '(?:T' . self::REGEX_PART_TIME . ')?';
	/** @internal */
	const REGEX_PART_NUMBER = '[0-9]+(?:[.,][0-9]{1,3})?';
	/** @internal */
	const REGEX_PART_TIME = '(?:(' . self::REGEX_PART_NUMBER . ')H)?(?:(' . self::REGEX_PART_NUMBER . ')M)?(?:(' . self::REGEX_PART_NUMBER . ')S)?';
	/** @internal */
	const REGEX_PART_WEEK = '(?:(' . self::REGEX_PART_NUMBER . ')W)';
	/** @internal */
	const SUFFIX_DAYS = 'D';
	/** @internal */
	const SUFFIX_HOURS = 'H';
	/** @internal */
	const SUFFIX_MINUTES = 'M';
	/** @internal */
	const SUFFIX_MONTHS = 'M';
	/** @internal */
	const SUFFIX_SECONDS = 'S';
	/** @internal */
	const SUFFIX_WEEKS = 'W';
	/** @internal */
	const SUFFIX_YEARS = 'Y';

	/**
	 * Returns whether the specified duration affects months only
	 *
	 * @param string $duration
	 * @return bool
	 */
	public static function affectsMonthsOnly($duration) {
		return \preg_match('/^' . self::PREFIX . self::REGEX_PART_NUMBER . self::SUFFIX_MONTHS . '$/', $duration) === 1;
	}

	/** Do not allow instantiation */
	private function __construct() {}

}
