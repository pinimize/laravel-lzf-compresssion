<?php

namespace Pinimize\Lzf\Tests\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Pinimize\Facades\Compression;
use Pinimize\Facades\Decompression;
use Pinimize\Lzf\Compression\LzfDriver as LzfCompressionDriver;
use Pinimize\Lzf\Decompression\LzfDriver as LzfDecompressionDriver;
use Pinimize\Lzf\Tests\TestCase;

class PinimizeLzfServiceProviderTest extends TestCase
{
    #[Test]
    public function it_extends_compression_manager_with_lzf_driver(): void
    {
        $this->assertInstanceOf(LzfCompressionDriver::class, Compression::driver('lzf'));
    }

    #[Test]
    public function it_extends_decompression_manager_with_lzf_driver(): void
    {
        $this->assertInstanceOf(LzfDecompressionDriver::class, Decompression::driver('lzf'));
    }

    #[Test]
    public function it_registers_lzf_drivers_with_correct_config(): void
    {
        putenv('COMPRESSION_DRIVER=lzf');
        putenv('COMPRESSION_DISK=test');
        $this->refreshApplication();

        Config::set('pinimize.compression.drivers.lzf', ['disk' => env('COMPRESSION_DISK')]);
        $this->assertInstanceOf(LzfCompressionDriver::class, Compression::driver());
        $this->assertInstanceOf(LzfDecompressionDriver::class, Decompression::driver());

        $this->assertEquals('test', Compression::getConfig()['disk']);
        $this->assertEquals('test', Decompression::getConfig()['disk']);
    }
}
