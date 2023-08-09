<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcb15b6c62e95267ffa1e9ceec064fb67
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Parsedown' => 
            array (
                0 => __DIR__ . '/..' . '/erusev/parsedown',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitcb15b6c62e95267ffa1e9ceec064fb67::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitcb15b6c62e95267ffa1e9ceec064fb67::$classMap;

        }, null, ClassLoader::class);
    }
}
