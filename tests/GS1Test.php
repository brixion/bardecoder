<?php

declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;

final class GS1Test extends TestCase
{
    private array $gs1 = [
        [
            "gs1" => "010019506205432717260806108356271",
            "product_code" => "00195062054327",
            "expiry_date" => "2026-08-06",
            "lot" => "8356271"
        ],
        [
            "gs1" => "01007073874690851724072210202407JV",
            "product_code" => "00707387469085",
            "expiry_date" => "2024-07-22",
            "lot" => "202407JV"
        ],
        [
            "gs1" => "01008170510223211121120210A63098",
            "product_code" => "0081705102232",
            "expiry_date" => "2023-11-04",
            "lot" => "A63098"
        ]
    ];



    /** @test */
    public function it_can_instantiate_gs1(): void
    {
        foreach ($this->gs1 as $gs1) {
            $decoder = new UdiDecoder($gs1["gs1"]);
            $this->assertInstanceOf(UdiDecoder::class, $decoder);
        }
    }
    /** @test */
    public function it_returns_gtin_from_gs1(): void
    {
        foreach ($this->gs1 as $gs1) {
            $decoder = new UdiDecoder($gs1["gs1"]);
            $this->assertEquals($gs1["product_code"], $decoder->product_code);
        }
    }
    /** @test */
    public function it_returns_expiry_date_from_gs1(): void
    {
        foreach ($this->gs1 as $gs1) {
            $decoder = new UdiDecoder($gs1["gs1"]);
            $this->assertEquals($gs1["expiry_date"], $decoder->expiry_date);
        }
    }
    /** @test */
    public function it_returns_lot_from_gs1(): void
    {
        foreach ($this->gs1 as $gs1) {
            $decoder = new UdiDecoder($gs1["gs1"]);
            $this->assertEquals($gs1["lot"], $decoder->lot);
        }
    }

    /**
     * @test
     * @dataProvider barcode_provider
     * Check if UdiDecoder can instantiate without exceptions using barcode_provider
     */
    public function test_all_barcodes($barcode): void
    {
        ini_set('memory_limit', '6000M');
        $decoder = new UdiDecoder($barcode);

        $this->assertInstanceOf(
            UdiDecoder::class,
            $decoder
        );
    }

    public function barcode_provider()
    {
        return include __DIR__ . "\data\LargeGS1BarcodesArray.php";
    }
}
