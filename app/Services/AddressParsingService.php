<?php

namespace App\Services;

class AddressParsingService
{
    public function getCountry(string $address): string | null
    {
        if ($address) {
            if (strpos($address, 'United States')) {
                return 'United States';
            }
            $parts = explode(' ', $address);
            if (in_array('US', $parts, true) || in_array('TX', $parts, true)) {
                return 'United States';
            }
        }
        return null;
    }
}
