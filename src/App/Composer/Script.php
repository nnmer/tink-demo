<?php

namespace App\Composer;

class Script
{
    public static function install()
    {
        try {
            mkdir('var/cache', 0755, true);
        }catch (\Exception $e) {

        }
    }
}