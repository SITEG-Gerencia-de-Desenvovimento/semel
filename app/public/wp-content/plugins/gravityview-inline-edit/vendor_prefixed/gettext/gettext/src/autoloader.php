<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 12-July-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

spl_autoload_register(function ($class) {
    if (strpos($class, 'GravityKit\\GravityEdit\\Foundation\\ThirdParty\\Gettext\\') !== 0) {
        return;
    }

    $file = __DIR__.str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen('GravityKit\GravityEdit\Foundation\ThirdParty\Gettext'))).'.php';

    if (is_file($file)) {
        require_once $file;
    }
});
