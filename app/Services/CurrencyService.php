<?php

namespace App\Services;

use App\Models\SystemConfig;

class CurrencyService
{
    public static function convert($amount, $toCurrency = 'USD', $fromCurrency = null)
    {
        $fromCurrency = $fromCurrency ?? SystemConfig::get('default_currency', 'PKR');

        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // We assume rates are stored as "rate per default currency (PKR)"
        // e.g. usd_rate = 0.0036 (1 PKR = 0.0036 USD)
        // If from PKR to USD: amount * usd_rate
        // If from USD to PKR: amount / usd_rate

        $usdRate = (float) SystemConfig::get('usd_rate', 0.0036);
        $eurRate = (float) SystemConfig::get('eur_rate', 0.0033);

        $pkrAmount = $amount;
        if ($fromCurrency === 'USD') {
            $pkrAmount = $amount / $usdRate;
        } elseif ($fromCurrency === 'EUR') {
            $pkrAmount = $amount / $eurRate;
        }

        if ($toCurrency === 'PKR') {
            return $pkrAmount;
        } elseif ($toCurrency === 'USD') {
            return $pkrAmount * $usdRate;
        } elseif ($toCurrency === 'EUR') {
            return $pkrAmount * $eurRate;
        }

        return $amount;
    }

    public static function getAllConversions($amount, $fromCurrency = null)
    {
        return [
            'PKR' => self::convert($amount, 'PKR', $fromCurrency),
            'USD' => self::convert($amount, 'USD', $fromCurrency),
            'EUR' => self::convert($amount, 'EUR', $fromCurrency),
        ];
    }
}
