<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit01883b1a733b1fbd15899ba87d5e9875
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit01883b1a733b1fbd15899ba87d5e9875::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit01883b1a733b1fbd15899ba87d5e9875::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit01883b1a733b1fbd15899ba87d5e9875::$classMap;

        }, null, ClassLoader::class);
    }
}