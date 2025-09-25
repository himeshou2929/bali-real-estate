<?php

namespace App\Support;

class Price
{
    public static function format($usd, $currency = 'USD')
    {
        switch ($currency) {
            case 'IDR':
                $amount = $usd * config('price.rates.IDR');
                return 'Rp ' . number_format($amount, 0, '.', ',');
                
            case 'JPY':
                $amount = $usd * config('price.rates.JPY');
                return '¥' . number_format($amount, 0, '.', ',');
                
            case 'USD':
            default:
                return '$' . number_format($usd, 0, '.', ',');
        }
    }
}