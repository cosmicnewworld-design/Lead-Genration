<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you remain free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure the cost factor for the bcrypt algorithm. This
    | controls how many computational rounds are used to generate the hash
    | value instead of simply using the default values provided by PHP.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure the options for the Argon2 algorithm. These
    | let you control the memory cost, time cost, and parallelism factor
    | of the algorithm instead of simply using the default PHP values.
    |
    */

    'argon' => [
        'memory' => 1024,
        'threads' => 2,
        'time' => 2,
    ],

];
