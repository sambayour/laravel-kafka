<?php

namespace App\Helpers;

class GeneralHelpers
{
    /**
     * checkStatusCode [Properly handle status code which comes with 0]
     *
     * @param  int $code
     * @return int
     */
    public static function checkStatusCode($code): int
    {
        $statusCode = (is_numeric($code) && $code > 0) ? $code : 500;
        return $statusCode;
    }
}
