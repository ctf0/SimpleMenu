<?php

/*
 * trim phrases in english or arabic
 */
if (!function_exists('trimfy')) {
    function trimfy($value, $limit = 100, $end = ' ...')
    {
        $value = strip_tags($value);

        if (config('app.locale') != 'en') {
            return strlen($value) <= $limit
                ? $value
                : mb_substr($value, 0, $limit, 'UTF-8').$end;
        }

        return mb_strwidth($value, 'UTF-8') <= $limit
            ? $value
            : rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
    }
}

/*
 * slug titles for english or arabic
 */
if (!function_exists('slugfy')) {
    function slugfy($string, $separator = '-')
    {
        $url = trim($string);
        $url = strtolower($url);
        $url = preg_replace('|[^a-z-A-Z\p{Arabic}0-9 _]|iu', '', $url);
        $url = preg_replace('/\s+/', ' ', $url);
        $url = str_replace(' ', $separator, $url);

        return $url;
    }
}
