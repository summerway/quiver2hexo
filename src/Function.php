<?php
/**
 * Created by PhpStorm.
 * User: MapleSnow
 * Date: 2019/3/6
 * Time: 11:01 PM
 */

namespace {

    const HEXO_LOCAL_SERVER = "http://localhost:4000";

    if (! function_exists('env')) {
        /**
         * Gets the value of an environment variable.
         *
         * @param  string  $key
         * @param  mixed   $default
         * @return mixed
         */
        function env($key, $default = null)
        {
            $value = getenv($key);

            if ($value === false) {
                return $default;
            }

            switch (strtolower($value)) {
                case 'true':
                case '(true)':
                    return true;
                case 'false':
                case '(false)':
                    return false;
                case 'empty':
                case '(empty)':
                    return '';
                case 'null':
                case '(null)':
                    return ;
            }

            return $value;
        }
    }
}