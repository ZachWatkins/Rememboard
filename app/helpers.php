<?php

/**
 * This file defines global helper functions.
 */

if (!function_exists('convertToUserTimezone')) {
    /**
     * Convert a datetime string to the user's timezone.
     *
     * @param string $datetime
     * @return string
     */
    function convertToUserTimezone(string $datetime): string
    {
        $userTimezone = \Illuminate\Support\Facades\Auth::user()->timezone; // Assuming the user has a 'timezone' attribute
        $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $userTimezone);
        return $timestamp->setTimezone('UTC')->toDateTimeString();
    }
}
