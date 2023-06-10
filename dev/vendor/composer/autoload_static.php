<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbac82fe669eb77c7c11d653907778927
{
    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'lib\\' => 4,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'lib\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbac82fe669eb77c7c11d653907778927::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbac82fe669eb77c7c11d653907778927::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbac82fe669eb77c7c11d653907778927::$classMap;

        }, null, ClassLoader::class);
    }
}