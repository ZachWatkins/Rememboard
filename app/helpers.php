<?php

/**
 * This file defines global helper functions.
 */

if (!function_exists('dateToSessionTime')) {
    /**
     * Convert a datetime string from UTC to the session's timezone.
     *
     * @param string $utcDatetime
     * @return string
     */
    function dateToSessionTime(?string $utcDatetime): string | null
    {
        if (!$utcDatetime) {
            return null;
        }
        $clientTimezoneOffset = session('timezoneOffset'); // minutes.
        if (auth()->user?->timezone) {
            $clientTimezoneOffset = (new \DateTimeZone(auth()->user->timezone))->getOffset(new \DateTime()) / 60;
        }
        if ($clientTimezoneOffset === 0) {
            return $utcDatetime;
        }
        if ($clientTimezoneOffset > 0) {
            return (new \DateTime($utcDatetime))->modify('+' . $clientTimezoneOffset . ' minutes')->format('Y-m-d\TH:i:s');
        }
        return (new \DateTime($utcDatetime))->modify($clientTimezoneOffset . ' minutes')->format('Y-m-d\TH:i:s');
    }
}

if (!function_exists('dateFromSessionTime')) {
    /**
     * Convert a datetime string from the session's timezone to UTC.
     *
     * @param string $sessionDatetime
     * @return string
     */
    function dateFromSessionTime(?string $sessionDatetime): string | null
    {
        if (!$sessionDatetime) {
            return null;
        }
        $clientTimezoneOffset = session('timezoneOffset'); // minutes.
        if (auth()->user()?->timezone) {
            $clientTimezoneOffset = (new \DateTimeZone(auth()->user->timezone))->getOffset(new \DateTime()) / 60;
        }
        if ($clientTimezoneOffset === 0) {
            return $sessionDatetime;
        }
        if ($clientTimezoneOffset > 0) {
            return (new \DateTime($sessionDatetime))->modify('-' . $clientTimezoneOffset . ' minutes')->format('Y-m-d\TH:i:s');
        }
        return (new \DateTime($sessionDatetime))->modify('+' . abs($clientTimezoneOffset) . ' minutes')->format('Y-m-d\TH:i:s');
    }
}
