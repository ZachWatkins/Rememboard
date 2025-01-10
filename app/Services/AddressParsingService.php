<?php

namespace App\Services;

class AddressParsingService
{
    public function getCity(string $address): string | null
    {
        if ($address) {
            $state = $this->getState($address);
            if ($state) {
                if (preg_match("/([a-zA-Z\s\-']+)[,\s]*{$state}/", $address, $matches)) {
                    return trim($matches[1], ' ,');
                }
            }
        }
        return null;
    }

    public function getState(string $address): string | null
    {
        if ($address) {
            if (preg_match('/\bTX\b|\bTexas\b/', $address, $matches)) {
                return $matches[0];
            }
        }
        return null;
    }

    public function getZip(string $address): string | null
    {
        if ($address) {
            if (preg_match('/\b(\d{5})(-\d{4})?\b/', $address, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

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
