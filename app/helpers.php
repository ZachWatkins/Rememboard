<?php

/**
 * This file defines global helper functions.
 */

if (!function_exists('convertFromUserTimezone')) {
    /**
     * Convert a datetime string from the user's timezone to UTC.
     *
     * @param string $datetime
     * @return string
     */
    function convertFromUserTimezone(?string $datetime): string | null
    {
        if (!$datetime) {
            return null;
        }
        $userTimezone = \Illuminate\Support\Facades\Auth::user()?->timezone;
        if (!$userTimezone) {
            return $datetime;
        }
        $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $userTimezone);
        return $timestamp->setTimezone('UTC')->toDateTimeString();
    }
}

if (!function_exists('convertToUserTimezone')) {
    /**
     * Convert a datetime string to the user's timezone.
     *
     * @param string $datetime
     * @return string
     */
    function convertToUserTimezone(?string $datetime): string | null
    {
        if (!$datetime) {
            return null;
        }
        $userTimezone = \Illuminate\Support\Facades\Auth::user()?->timezone;
        if (!$userTimezone) {
            return $datetime;
        }
        $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime, 'UTC');
        return $timestamp->setTimezone($userTimezone)->toDateTimeString();
    }
}

if (!function_exists('convertFromTimezone')) {
    /**
     * Convert a datetime string to UTC from the given timezone.
     *
     * @param string $datetime
     * @param string $timezone
     * @return string
     */
    function convertFromTimezone(string $datetime, string $timezone): string
    {
        $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $datetime, $timezone);
        return $timestamp->setTimezone('UTC')->toDateTimeString();
    }
}
