<?php

namespace Config;

use Midtrans\Config;

class Midtrans
{
    public static function init()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');

        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');

        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') == 'true';

        Config::$isSanitized = true;

        Config::$is3ds = true;
    }
}