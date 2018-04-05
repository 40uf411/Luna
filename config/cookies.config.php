<?php

use Luna\Providers\CookiesProvider as CookiesProvider;

CookiesProvider::config
([
    // default cookies name prefix to be used in the name of the cookies

    'DEFAULT_PREFIX'        => 'luna_',

    // default cookies expire time you can use a number like ( 50, 150, ...) or a number followed by a letter ( s, m, h, d, M, y)

    'DEFAULT_EXPIRE_TIME'   => 3600,

    // default cookies path

    'DEFAULT_PATH'          => null,

    // default cookies domain

    'DEFAULT_DOMAIN'        => null,

    // default cookies secure option

    'DEFAULT_SECURE'        => false,

    // default httponly option

    'DEFAULT_HTTP_ONLY'     => false,
]);
