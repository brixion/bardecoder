<?php

declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;

final class GS1Test extends TestCase
{
    // Why are there <gs> in the barcodes present while they are not readable in the human-readable code itself?
    // Because <gs> is the character that signifies an end of a part.
    private array $gs1 = [
        [
            "gs1" => "010403507700171717240117108317326",
            "product_code" => "46100",
            "expiry_date" => "2024-01-17",
            "lot" => "8317326",
        ],
        [
            "gs1" => "010019506205432717260806108356271<gs>",
            "product_code" => "19506205432",
            "expiry_date" => "2026-08-06",
            "lot" => "8356271"
        ],
        [
            "gs1" => "01007073874690851724072210202407JV<gs>",
            "product_code" => "70738746908",
            "expiry_date" => "2024-07-22",
            "lot" => "202407JV"
        ],
        [
            "gs1" => "(01)0081705102232(11)121120210A63098<gs>",
            "product_code" => "81705102232",
            "expiry_date" => "2012-12-20",
            "expiry_date" => null,
            "lot" => "A63098"
        ],
        [
            "gs1" => "(01)00841396198953108117117<gs>(17)230301",
            "product_code" => "84139619895",
            "expiry_date" => "2023-03-01",
            "lot" => "8117117",
        ],
        [
            "gs1" => "(01)17310230007657(10)2136009<gs>(17)200926",
            "product_code" => "1731023000765",
            "expiry_date" => "2020-09-26",
            "lot" => "2136009",
        ],
        [
            "gs1" => "010406223417459510K010034<gs>112203151725022324066008292",
            "product_code" => "66008292",
            "expiry_date" => "2025-02-23",
            "date_of_manufacture" => "2022-03-15",
            "lot" => "K010034"
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

    // /**
    //  * test
    //  * @dataProvider barcode_provider
    //  * Check if UdiDecoder can instantiate without exceptions using barcode_provider
    //  */
    // public function test_all_barcodes($barcode): void
    // {
    //     ini_set('memory_limit', '6000M');
    //     $decoder = new UdiDecoder($barcode);

    //     $this->assertInstanceOf(
    //         UdiDecoder::class,
    //         $decoder
    //     );
    // }

    // public function barcode_provider()
    // {
    //     return include __DIR__ . "\data\LargeGS1BarcodesArray.php";
    // }
}
