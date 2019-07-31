<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7fcbf072a039d7c20d977b85ade71b0c
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Code\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Code\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Code\\Controller\\HomeController' => __DIR__ . '/../..' . '/src/Controller/HomeController.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7fcbf072a039d7c20d977b85ade71b0c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7fcbf072a039d7c20d977b85ade71b0c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7fcbf072a039d7c20d977b85ade71b0c::$classMap;

        }, null, ClassLoader::class);
    }
}
