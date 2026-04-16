<?php

return [
    /*
    |--------------------------------------------------------------------------
    | QR Code Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the default settings for QR codes.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Image Backend
    |--------------------------------------------------------------------------
    |
    | This option controls the default image backend that should be used
    | by the QR code generator. By default, we'll use the GD backend
    | as it's more commonly available than Imagick.
    |
    */
    'backend' => 'gd',

    /*
    |--------------------------------------------------------------------------
    | Default Format
    |--------------------------------------------------------------------------
    |
    | This option controls the default format that should be used
    | by the QR code generator.
    |
    */
    'format' => 'png',

    /*
    |--------------------------------------------------------------------------
    | Default Size
    |--------------------------------------------------------------------------
    |
    | This option controls the default size that should be used
    | by the QR code generator.
    |
    */
    'size' => 150,

    /*
    |--------------------------------------------------------------------------
    | Default Error Correction
    |--------------------------------------------------------------------------
    |
    | This option controls the default error correction level that should be used
    | by the QR code generator. Available levels: L, M, Q, H
    |
    */
    'error_correction' => 'H',
];
