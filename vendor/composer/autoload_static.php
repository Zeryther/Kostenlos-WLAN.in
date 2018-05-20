<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbf28b8b9bf15a5af997abb12becd7b6f
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpFastCache\\' => 13,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Cache\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpFastCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpfastcache/phpfastcache/src/phpFastCache',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Monolog' => 
            array (
                0 => __DIR__ . '/..' . '/monolog/monolog/src',
            ),
        ),
        'J' => 
        array (
            'JasonGrimes' => 
            array (
                0 => __DIR__ . '/..' . '/jasongrimes/paginator/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbf28b8b9bf15a5af997abb12becd7b6f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbf28b8b9bf15a5af997abb12becd7b6f::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitbf28b8b9bf15a5af997abb12becd7b6f::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}