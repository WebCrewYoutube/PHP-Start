<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3b4c10f9b6d16b743f80e2bb8b1bfd4b
{
    public static $files = array (
        '8121a2fe6a6ea25290529926126216f4' => __DIR__ . '/../..' . '/src/browse.php',
    );

    public static $prefixLengthsPsr4 = array (
        'w' => 
        array (
            'webcrew\\Test\\' => 13,
            'webcrew\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'webcrew\\Test\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'webcrew\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3b4c10f9b6d16b743f80e2bb8b1bfd4b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3b4c10f9b6d16b743f80e2bb8b1bfd4b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
