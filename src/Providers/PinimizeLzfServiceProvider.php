<?php

declare(strict_types=1);

namespace Pinimize\Lzf\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Pinimize\Contracts\CompressionContract;
use Pinimize\Contracts\DecompressionContract;
use Pinimize\Facades\Compression;
use Pinimize\Facades\Decompression;
use Pinimize\Lzf\Compression\LzfDriver as LzfCompressionDriver;
use Pinimize\Lzf\Decompression\LzfDriver as LzfDecompressionDriver;

class PinimizeLzfServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Compression::extend(
            'lzf',
            fn (Application $application): CompressionContract => new LzfCompressionDriver($application['config']['pinimize']['compression']['drivers']['lzf'] ?? []),
        );
        Decompression::extend(
            'lzf',
            fn (Application $application): DecompressionContract => new LzfDecompressionDriver($application['config']['pinimize']['compression']['drivers']['lzf'] ?? []),
        );
    }
}
