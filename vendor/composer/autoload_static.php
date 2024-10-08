<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8de09183ba77b4d932139827356875bb
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Comercio\\Api\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Comercio\\Api\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8de09183ba77b4d932139827356875bb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8de09183ba77b4d932139827356875bb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8de09183ba77b4d932139827356875bb::$classMap;

        }, null, ClassLoader::class);
    }
}
