<?php

namespace App\Helpers;

class EmailHelper
{
    /**
     * Validasi email yang kompatibel dengan SQLite
     */
    public static function isValidEmail($email)
    {
        return $email && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Filter collection siswa berdasarkan email valid
     */
    public static function filterValidEmails($collection)
    {
        return $collection->filter(function($item) {
            return self::isValidEmail($item->email);
        });
    }

    /**
     * Count siswa dengan email valid dari query builder
     */
    public static function countValidEmails($query)
    {
        return $query->get()->filter(function($item) {
            return self::isValidEmail($item->email);
        })->count();
    }
}