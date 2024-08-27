<?php

namespace Pinimize\Lzf\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pinimize\Lzf\Providers\PinimizeLzfServiceProvider;
use Pinimize\Providers\PinimizeServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PinimizeServiceProvider::class,
            PinimizeLzfServiceProvider::class,
        ];
    }
}
