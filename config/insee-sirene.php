<?php

return [
    /*
    |--------------------------------------------------------------------------
    | INSEE Sirene API Key
    |--------------------------------------------------------------------------
    |
    | To interact with the INSEE APIs, you must first obtain a valid API key.
    | This key is used to authenticate your requests and must be included in
    | each call to the API. You can request an API key by registering on the
    | INSEE developer portal.
    |
    */
    'key' => '',

    /*
    |--------------------------------------------------------------------------
    | Rate limiting
    |--------------------------------------------------------------------------
    |
    | The usage of the Sirene API is limited to 30 requests per minute.
    | By setting this variable to TRUE, the library automatically manages this
    | limitation by suspending program execution temporarily when necessary.
    |
    */
    'retry_on_rate_limit' => true,

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Float describing the total timeout in seconds.
    | Use 0 to wait indefinitely (the default behavior).
    |
    */
    'timeout' => null,
];
