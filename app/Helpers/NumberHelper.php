<?php

namespace App\Helpers;

class NumberHelper
{
    /**
     * Format large numbers with M (millions) and B (billions) notation
     * 
     * @param float $number
     * @param int $decimals
     * @return string
     */
    public static function formatLargeNumber($number, $decimals = 1)
    {
        if ($number < 1000) {
            return number_format($number, 0);
        }
        
        if ($number < 1000000) {
            // Thousands (K)
            if ($number >= 100000) {
                // For 100K+, show no decimals for cleaner look
                return number_format($number / 1000, 0) . 'K';
            }
            return number_format($number / 1000, $decimals) . 'K';
        }
        
        if ($number < 1000000000) {
            // Millions (M)
            return number_format($number / 1000000, $decimals) . 'M';
        }
        
        if ($number < 1000000000000) {
            // Billions (B)
            return number_format($number / 1000000000, $decimals) . 'B';
        }
        
        // Trillions (T)
        return number_format($number / 1000000000000, $decimals) . 'T';
    }

    /**
     * Format currency with M/B notation
     * 
     * @param float $amount
     * @param string $currency
     * @param int $decimals
     * @return string
     */
    public static function formatCurrency($amount, $currency = '$', $decimals = 1)
    {
        return $currency . self::formatLargeNumber($amount, $decimals);
    }
}