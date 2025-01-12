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
    function dateToSessionTime(?string $utcDatetime, ?\App\Models\User $user = null): string | null
    {
        if (!$utcDatetime) {
            return null;
        }
        $clientTimezoneOffset = session('timezoneOffset'); // minutes.
        if ($user?->timezone) {
            $clientTimezoneOffset = (new \DateTimeZone($user->timezone))->getOffset(new \DateTime()) / 60;
        }
        if ($clientTimezoneOffset === null || $clientTimezoneOffset === 0) {
            return $utcDatetime;
        }
        if ($clientTimezoneOffset > 0) {
            return (new \DateTime($utcDatetime))->modify('+' . $clientTimezoneOffset . ' minutes')->format('Y-m-d H:i:s');
        }
        return (new \DateTime($utcDatetime))->modify($clientTimezoneOffset . ' minutes')->format('Y-m-d H:i:s');
    }
}

if (!function_exists('dateFromSessionTime')) {
    /**
     * Convert a datetime string from the session's timezone to UTC.
     *
     * @param string $sessionDatetime
     * @return string
     */
    function dateFromSessionTime(?string $sessionDatetime, ?\App\Models\User $user = null): string | null
    {
        if (!$sessionDatetime) {
            return null;
        }
        if ($user?->timezone) {
            return dateFromTimezone($user->timezone, $sessionDatetime);
        }
        $clientTimezoneOffset = session('timezoneOffset'); // minutes.
        if ($clientTimezoneOffset === null || $clientTimezoneOffset === 0) {
            return $sessionDatetime;
        }
        if ($clientTimezoneOffset > 0) {
            return (new \DateTime($sessionDatetime))->modify('-' . $clientTimezoneOffset . ' minutes')->format('Y-m-d H:i:s');
        }
        return (new \DateTime($sessionDatetime))->modify('+' . abs($clientTimezoneOffset) . ' minutes')->format('Y-m-d H:i:s');
    }
}

if (!function_exists('dateFromTimezone')) {
    /**
     * Convert a datetime string from a timezone to the application's timezone.
     *
     * @param string $datetime
     * @param string $timezone
     * @return string
     */
    function dateFromTimezone(string $datetime, ?string $timezone = null): string
    {
        if (!$timezone) {
            return $datetime;
        }
        return (new \DateTime($datetime, new \DateTimeZone($timezone)))->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
    }
}
