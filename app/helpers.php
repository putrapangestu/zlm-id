<?php

if (!function_exists('taxRate')) {
    function taxRate(): float
    {
        return (float) (config('settings.tax_rate', 11));
    }
}

if (!function_exists('calculateTax')) {
    function calculateTax(float $amount): float
    {
        return round($amount * taxRate() / 100, 2);
    }
}
