<?php

/*
 * PHP-Temporal (https://github.com/delight-im/PHP-Temporal)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Temporal\Iso8601;

/** Constants and utilities for working with date and time as per ISO 8601 */
final class Iso8601DateTime {

	const FORMAT_BASIC = 'Ymd\THisO';
	const FORMAT_EXTENDED = 'Y-m-d\TH:i:sP';

	/** Do not allow instantiation */
	private function __construct() {}

}
