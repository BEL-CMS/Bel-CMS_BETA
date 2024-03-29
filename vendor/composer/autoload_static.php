<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2501fa2461ff79aa32b0dfabdacd5012
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'core\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
    );

    public static $classMap = array (
        'PHP_Timer' => __DIR__ . '/..' . '/phpunit/php-timer/src/Timer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2501fa2461ff79aa32b0dfabdacd5012::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2501fa2461ff79aa32b0dfabdacd5012::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2501fa2461ff79aa32b0dfabdacd5012::$classMap;

        }, null, ClassLoader::class);
    }
}
