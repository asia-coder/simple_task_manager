<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit53c43eb8fc680fbbbac2395487407cc1
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Task\\' => 5,
        ),
        'K' => 
        array (
            'Klein\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Task\\' => 
        array (
            0 => __DIR__ . '/../..' . '/task',
        ),
        'Klein\\' => 
        array (
            0 => __DIR__ . '/..' . '/klein/klein/src/Klein',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit53c43eb8fc680fbbbac2395487407cc1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit53c43eb8fc680fbbbac2395487407cc1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
